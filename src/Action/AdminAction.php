<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;
use Carbon\Carbon;
use Slim\Http\Stream;

class AdminAction
{
    protected $options;
    protected $representation;
    protected $helper;
    protected $authorization;
    protected $db;
    protected $filesystem;
    protected $pagination;
    
    public function __construct(
    $options, $representation, $helper, $authorization, $db, $filesystem, $pagination
    ) {
        $this->options = $options;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->db = $db;
        $this->filesystem = $filesystem;
        $this->pagination = $pagination;
    }
    
    public function getOptions($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $options = $this->options->getOptions();
        return $response->withJSON($options->toArray());
    }
    
    public function getOption($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        $option = $this->options->getOption($opt);
        return $response->withJSON($option->toArray());
    }
    
    public function postOption($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'retOpt')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        $val = $this->helper->getSanitizedStr('value', $request->getParsedBody());
        $option = $this->options->getOption($opt);
        $option->value = $val;
        $option->save();
        return $this->representation->returnMessage($request, $response, [
        'message' => 'Opción actualizada',
        'status' => 200,
        ]);
    }
    
    // public function getDniList($request, $response, $params)
    // {
    //     $subject = $request->getAttribute('subject');
    //     if (!$this->authorization->checkPermission($subject, 'retDni')) {
    //         throw new UnauthorizedException();
    //     }
    //     $options = $this->pagination->getParams($request, [
    //         's' => [
    //             'type' => 'string',
    //         ],
    //     ]);
    //     $query = $this->db->query('App:User')->where('verified_dni', false);
    //     if (isset($options['s'])) {
    //         $filter = $this->helper->generateTrace($options['s']);
    //         $query->where('trace', 'LIKE', "%$filter%");
    //     }
    //     $results = new Paginator($query, $options);
    //     $results->setUri($request->getUri());
    //     return $this->pagination->renderResponse($response, $results);
    // }
    
    public function postVerifiedDni($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId('App:User', 'usr', $params);
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $user->verified_dni = true;
        $user->save();
        $group = $user->groups()->first();
        if (isset($group)) {
            $countMembers = $group->users()->count();
            $countVerified = $group->users()->where('verified_dni', true)->count();
            if ($countMembers >= 5 && $countMembers == $countVerified) {
                $group->verified_team = true;
                $group->save();
            }
        }
        return $this->representation->returnMessage($request, $response, [
        'message' => 'DNI verificado',
        'status' => 200,
        ]);
    }
    
    public function getMatriz($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        if (!in_array($opt, ['proyectos', 'equipos'])) {
            throw new AppException('No existe el dataset solicitado', 404);
        }
        $writer = \Box\Spout\Writer\WriterFactory::create(\Box\Spout\Common\Type::XLSX);
        $path = $opt.'.xlsx';
        if ($this->filesystem->has($path)) {
            $updDate = Carbon::createFromTimestamp($this->filesystem->getTimestamp($path));
            $expLimit = new Carbon('+ 4 hours');
            if ($updDate->gt($expLimit)) {
                return $response
                ->withBody(new Stream($this->filesystem->readStream($path)))
                ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            } else {
                $this->filesystem->delete($path);
            }
        }
        $this->filesystem->copy('sample.xlsx', $path);
        $tmpHandle = $this->filesystem->readStream($path);
        $metaDatas = stream_get_meta_data($tmpHandle);
        $tmpFilename = $metaDatas['uri'];
        $defStyle = (new \Box\Spout\Writer\Style\StyleBuilder())->setShouldWrapText()->build();
        $writer->openToFile($tmpFilename);
        
        if ($opt == 'proyectos') {
            $grupos = $this->db->query('App:Group', [
            'users', 'locality.department', 'project.locality.department'
            ])->get();
            $writer->addRow([
            'ID', 'Nombre equipo', 'Info equipo',
            'Región equipo', 'Departamento equipo', 'Localidad equipo',
            'Año de conformación', 'Ediciones anteriores participando',
            'Email equipo', 'Teléfono equipo', 'Web equipo', 'Facebook equipo',
            'Organización que pertenece el equipo', 'Temas trabajo organización',
            // 'Región organización', 'Departamento organización', 'Localidad organización',
            'Email organización', 'Telófono organización',
            'Facebook organización', 'Web organización',
            'Nombre del proyecto', 'Resumen', 'Fundamentación',
            'Temática', 'Trabajo ya ejecutado',
            'Región proyecto', 'Departamento proyecto',
            'Localidad proyecto', 'Barrios',
            'Objetivos', 'Calendario actividades', 'Presupuesto', 'Monto solicitado',
            'Organización que colabora', 'Temas de trabajo org colaboradora',
            // 'Región org colaboradora', 'Departamento org colaboradora', 'Localidad org colaboradora',
            'Email org colaboradora', 'Teléfono org colaboradora',
            'Facebook org colaboradora', 'Web org colaboradora', 
            'Cantidad de integrantes', '¿Equipo completo?', '¿Responsable asignado?',
            '¿DNI validados de todo el equipo?', '¿Cartas cargadas?',
            '¿Cargó imagen?', 'Puntaje', 'Rendición enviada', 'Rendición aprobada',
            'Observaciones', 'Condición', 'Monto otorgado'
            ]);
            foreach ($grupos as $gro) {
                if (isset($gro->project)) {
                    $pro = $gro->project;
                } else {
                    continue;
                }
                $insert = [
                    $gro->id, $gro->name, $gro->description,
                    $gro->locality->department->region_id, $gro->locality->department->name, $gro->locality->custom? $gro->locality_other: $gro->locality->name,
                    $gro->year, implode(', ', $gro->previous_editions),
                    $gro->email, $gro->telephone, $gro->web, $gro->facebook,
                ];
                if (isset($gro->parent_organization)) {
                    $temp = [
                        $gro->parent_organization['name'],
                        implode(', ', $gro->parent_organization['topics']),
                        $gro->parent_organization['email'] ?? null,
                        $gro->parent_organization['telephone'] ?? null,
                        $gro->parent_organization['facebook'] ?? null,
                        $gro->parent_organization['web'] ?? null,
                    ];
                } else {
                    $temp = [null, null, null, null, null, null];
                }
                $insert = array_merge($insert, $temp);
                $temp = [
                    $pro->name, $pro->abstract, $pro->foundation,
                    $pro->category? $pro->category->name: null, $pro->previous_work,
                    $pro->locality->department->region_id, $pro->locality->department->name,
                    $pro->locality->custom? $pro->locality_other: $pro->locality->name, implode(', ', $pro->neighbourhoods),
                    substr(json_encode($pro->goals), 0, 32000), substr(json_encode($pro->schedule), 0, 32000), substr(json_encode($pro->budget), 0, 32000), $pro->total_budget,
                ];
                $insert = array_merge($insert, $temp);
                if (isset($pro->organization)) {
                    $temp = [
                        $pro->organization['name'],
                        implode(', ', $pro->organization['topics']),
                        $pro->organization['email'] ?? null,
                        $pro->organization['telephone'] ?? null,
                        $pro->organization['facebook'] ?? null,
                        $pro->organization['web'] ?? null,
                    ];
                } else {
                    $temp = [null, null, null, null, null, null];
                }
                $insert = array_merge($insert, $temp);
                $temp = [
                    $gro->users->count(), $gro->full_team? 'SI': 'NO', $gro->second_in_charge? 'SI': 'NO',
                    $gro->verified_team? 'SI': 'NO', ($gro->uploaded_agreement && $gro->uploaded_letter)? 'SI': 'NO',
                    $pro->has_image? 'SI': 'NO', $gro->quota, $pro->budget_sent? 'SI': 'NO', $pro->budget_approved? 'SI': 'NO',
                    $pro->notes, $pro->selected? 'Seleccionado': ($gro->quota? 'Rechazado': 'No evaluado'), $pro->granted_budget,
                ];
                $insert = array_merge($insert, $temp);
                $writer->addRowWithStyle($insert, $defStyle);
            }
        } elseif ($opt == 'equipos') {
            $grupos = $this->db->query('App:Group', [
                'users.locality.department', 'project'
            ])->get();
            $writer->addRow([
            'ID proyecto', 'Nombre del equipo', 'Cargo en equipo',
            'DNI', 'Nombre/s', 'Apellidos/s',
            'Región',
            'Departamento',
            'Localidad',
            'Barrio', 'Dirección',
            'Fecha de nacimiento', 'Edad',
            'Género', 'Teléfono', 'Email', 'Facebook',
            'Proyecto seleccionado'
            ]);
            foreach ($grupos as $gro) {
                if (is_null($gro->project)) {
                    continue;
                }
                foreach ($gro->users as $usr) {
                    $cumple = Carbon::parse($usr->birthday);
                    $writer->addRowWithStyle([
                    $gro->project->id, $gro->name, $usr->pivot->relation,
                    $usr->dni, $usr->names, $usr->surnames,
                    $usr->locality->department->region_id,
                    $usr->locality->department->name,
                    $usr->locality->custom? $usr->locality_other: $usr->locality->name,
                    $usr->neighbourhood, $usr->address,
                    $cumple->toDateString(), $cumple->age,
                    $usr->gender, $usr->telephone, $usr->email, $usr->facebook,
                    $gro->project->selected ? 'SI' : 'NO'
                    ], $defStyle);
                }
            }
        }
        
        $writer->close();
        return $response
        ->withBody(new Stream($tmpHandle))
        ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->withHeader('Content-Disposition', 'attachment; filename='.$path);
    }
}