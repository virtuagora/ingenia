<?php namespace App\Util;

class LocalitiesLoader
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function up()
    {
        $this->db->table('regions')->insert([
            ['id' => 1, 'region' => 1, 'name' => 'Reconquista'],
            ['id' => 2, 'region' => 2, 'name' => 'Rafaela'],
            ['id' => 3, 'region' => 3, 'name' => 'Santa Fe'],
            ['id' => 4, 'region' => 4, 'name' => 'Rosario'],
            ['id' => 5, 'region' => 5, 'name' => 'Venado Tuerto'],
        ]);
        $this->db->table('departments')->insert([
            ['id' => 1, 'region_id' => 1, 'name' => 'General Obligado'],
            ['id' => 2, 'region_id' => 1, 'name' => 'San Javier'],
            ['id' => 3, 'region_id' => 1, 'name' => 'Vera'],
            ['id' => 4, 'region_id' => 2, 'name' => '9 de Julio'],
            ['id' => 5, 'region_id' => 2, 'name' => 'Castellanos'],
            ['id' => 6, 'region_id' => 2, 'name' => 'San Cristobal'],
            ['id' => 7, 'region_id' => 2, 'name' => 'San Martín'],
            ['id' => 8, 'region_id' => 3, 'name' => 'Garay'],
            ['id' => 9, 'region_id' => 3, 'name' => 'La Capital'],
            ['id' => 10, 'region_id' => 3, 'name' => 'Las Colonias'],
            ['id' => 11, 'region_id' => 3, 'name' => 'San Javier'],
            ['id' => 12, 'region_id' => 3, 'name' => 'San Jerónimo'],
            ['id' => 13, 'region_id' => 3, 'name' => 'San Justo'],
            ['id' => 14, 'region_id' => 4, 'name' => 'Belgrano'],
            ['id' => 15, 'region_id' => 4, 'name' => 'Caseros'],
            ['id' => 16, 'region_id' => 4, 'name' => 'Constitución'],
            ['id' => 17, 'region_id' => 4, 'name' => 'Iriondo'],
            ['id' => 18, 'region_id' => 4, 'name' => 'Rosario'],
            ['id' => 19, 'region_id' => 4, 'name' => 'San Jerónimo'],
            ['id' => 20, 'region_id' => 4, 'name' => 'San Lorenzo'],
            ['id' => 21, 'region_id' => 4, 'name' => 'San Martín'],
            ['id' => 22, 'region_id' => 5, 'name' => 'Caseros'],
            ['id' => 23, 'region_id' => 5, 'name' => 'Constitución'],
            ['id' => 24, 'region_id' => 5, 'name' => 'General Lopez'],
        ]);
        $this->db->table('localities')->insert([
            ['department_id' => 1, 'name' => 'Arroyo Ceibal'],
            ['department_id' => 1, 'name' => 'Avellaneda'],
            ['department_id' => 1, 'name' => 'Berna'],
            ['department_id' => 1, 'name' => 'El Arazá'],
            ['department_id' => 1, 'name' => 'El Rabón'],
            ['department_id' => 1, 'name' => 'El Sombrerito'],
            ['department_id' => 1, 'name' => 'Florencia'],
            ['department_id' => 1, 'name' => 'Guadalupe Norte'],
            ['department_id' => 1, 'name' => 'Ingeniero Chanourdie'],
            ['department_id' => 1, 'name' => 'La Sarita'],
            ['department_id' => 1, 'name' => 'Lanteri'],
            ['department_id' => 1, 'name' => 'Las Garzas'],
            ['department_id' => 1, 'name' => 'Las Toscas'],
            ['department_id' => 1, 'name' => 'Los Laureles'],
            ['department_id' => 1, 'name' => 'Malabrigo'],
            ['department_id' => 1, 'name' => 'Nicanor Molinas'],
            ['department_id' => 1, 'name' => 'Reconquista'],
            ['department_id' => 1, 'name' => 'San Antonio de Obligado'],
            ['department_id' => 1, 'name' => 'Tacuarendí'],
            ['department_id' => 1, 'name' => 'Villa Ana'],
            ['department_id' => 1, 'name' => 'Villa Guillermina'],
            ['department_id' => 1, 'name' => 'Villa Ocampo'],
            ['department_id' => 2, 'name' => 'Alejandra'],
            ['department_id' => 2, 'name' => 'Colonia Durán'],
            ['department_id' => 2, 'name' => 'Romang'],
            ['department_id' => 3, 'name' => 'Calchaquí'],
            ['department_id' => 3, 'name' => 'Cañada Ombú'],
            ['department_id' => 3, 'name' => 'Fortín Olmos'],
            ['department_id' => 3, 'name' => 'Garabato'],
            ['department_id' => 3, 'name' => 'Golondrina'],
            ['department_id' => 3, 'name' => 'Intiyaco'],
            ['department_id' => 3, 'name' => 'La Gallareta'],
            ['department_id' => 3, 'name' => 'Los Amores'],
            ['department_id' => 3, 'name' => 'Margarita'],
            ['department_id' => 3, 'name' => 'Tartagal'],
            ['department_id' => 3, 'name' => 'Toba'],
            ['department_id' => 3, 'name' => 'Vera'],
            ['department_id' => 4, 'name' => 'Campo Garay (Juan de Garay)'],
            ['department_id' => 4, 'name' => 'Esteban Rams'],
            ['department_id' => 4, 'name' => 'Gato Colorado'],
            ['department_id' => 4, 'name' => 'Gregoria Pérez de Denis'],
            ['department_id' => 4, 'name' => 'Logroño'],
            ['department_id' => 4, 'name' => 'Montefiore'],
            ['department_id' => 4, 'name' => 'Pozo Borrado'],
            ['department_id' => 4, 'name' => 'San Bernardo'],
            ['department_id' => 4, 'name' => 'Santa Margarita'],
            ['department_id' => 4, 'name' => 'Tostado'],
            ['department_id' => 4, 'name' => 'Villa Minetti'],
            ['department_id' => 5, 'name' => 'Aldao (Colonia)'],
            ['department_id' => 5, 'name' => 'Angélica'],
            ['department_id' => 5, 'name' => 'Ataliva'],
            ['department_id' => 5, 'name' => 'Aurelia'],
            ['department_id' => 5, 'name' => 'Bauer y Sigel'],
            ['department_id' => 5, 'name' => 'Bella Italia'],
            ['department_id' => 5, 'name' => 'Bicha (Colonia)'],
            ['department_id' => 5, 'name' => 'Bigand (Colonia)'],
            ['department_id' => 5, 'name' => 'Castellanos (Colonia)'],
            ['department_id' => 5, 'name' => 'Clucellas (Plaza)'],
            ['department_id' => 5, 'name' => 'Colonia Cello'],
            ['department_id' => 5, 'name' => 'Colonia Iturraspe'],
            ['department_id' => 5, 'name' => 'Colonia Raquel'],
            ['department_id' => 5, 'name' => 'Coronel Fraga'],
            ['department_id' => 5, 'name' => 'Egusquiza'],
            ['department_id' => 5, 'name' => 'Esmeralda'],
            ['department_id' => 5, 'name' => 'Estación Clucellas'],
            ['department_id' => 5, 'name' => 'Eusebia'],
            ['department_id' => 5, 'name' => 'Eustolia'],
            ['department_id' => 5, 'name' => 'Fidela (Colonia)'],
            ['department_id' => 5, 'name' => 'Frontera'],
            ['department_id' => 5, 'name' => 'Galisteo'],
            ['department_id' => 5, 'name' => 'Garibaldi'],
            ['department_id' => 5, 'name' => 'Hugentobler (Colonia)'],
            ['department_id' => 5, 'name' => 'Humberto Primo'],
            ['department_id' => 5, 'name' => 'Josefina'],
            ['department_id' => 5, 'name' => 'Lehmann'],
            ['department_id' => 5, 'name' => 'Margarita (Colonia)'],
            ['department_id' => 5, 'name' => 'María Juana'],
            ['department_id' => 5, 'name' => 'Mauá (Colonia)'],
            ['department_id' => 5, 'name' => 'Presidente Roca'],
            ['department_id' => 5, 'name' => 'Pueblo Marini'],
            ['department_id' => 5, 'name' => 'Rafaela'],
            ['department_id' => 5, 'name' => 'Ramona'],
            ['department_id' => 5, 'name' => 'Saguier (Estación)'],
            ['department_id' => 5, 'name' => 'San Antonio (Pueblo)'],
            ['department_id' => 5, 'name' => 'San Vicente'],
            ['department_id' => 5, 'name' => 'Santa Clara de Saguier'],
            ['department_id' => 5, 'name' => 'Sunchales'],
            ['department_id' => 5, 'name' => 'Susana'],
            ['department_id' => 5, 'name' => 'Tacural'],
            ['department_id' => 5, 'name' => 'Tacurales (Colonia)'],
            ['department_id' => 5, 'name' => 'Vila'],
            ['department_id' => 5, 'name' => 'Villa San José'],
            ['department_id' => 5, 'name' => 'Virginia'],
            ['department_id' => 5, 'name' => 'Zenón Pereyra'],
            ['department_id' => 6, 'name' => 'Aguará Grande'],
            ['department_id' => 6, 'name' => 'Ambrosetti'],
            ['department_id' => 6, 'name' => 'Arrufó'],
            ['department_id' => 6, 'name' => 'Capivara'],
            ['department_id' => 6, 'name' => 'Ceres'],
            ['department_id' => 6, 'name' => 'Colonia Ana'],
            ['department_id' => 6, 'name' => 'Colonia Bossi'],
            ['department_id' => 6, 'name' => 'Colonia Dos Rosas y La Legua'],
            ['department_id' => 6, 'name' => 'Colonia Rosa'],
            ['department_id' => 6, 'name' => 'Constanza'],
            ['department_id' => 6, 'name' => 'Curupaity'],
            ['department_id' => 6, 'name' => 'Hersilia'],
            ['department_id' => 6, 'name' => 'Huanqueros'],
            ['department_id' => 6, 'name' => 'La Cabral'],
            ['department_id' => 6, 'name' => 'La Clara (Colonia)'],
            ['department_id' => 6, 'name' => 'La Lucila'],
            ['department_id' => 6, 'name' => 'La Rubia'],
            ['department_id' => 6, 'name' => 'Las Avispas'],
            ['department_id' => 6, 'name' => 'Las Palmeras'],
            ['department_id' => 6, 'name' => 'Moisés Ville'],
            ['department_id' => 6, 'name' => 'Monigotes'],
            ['department_id' => 6, 'name' => 'Monte Obscuridad'],
            ['department_id' => 6, 'name' => 'Ñanducita'],
            ['department_id' => 6, 'name' => 'Palacios'],
            ['department_id' => 6, 'name' => 'Portugalete'],
            ['department_id' => 6, 'name' => 'San Cristóbal'],
            ['department_id' => 6, 'name' => 'San Guillermo'],
            ['department_id' => 6, 'name' => 'Santurce'],
            ['department_id' => 6, 'name' => 'Soledad'],
            ['department_id' => 6, 'name' => 'Suardi'],
            ['department_id' => 6, 'name' => 'Villa Saralegui'],
            ['department_id' => 6, 'name' => 'Villa Trinidad'],
            ['department_id' => 7, 'name' => 'Castelar'],
            ['department_id' => 7, 'name' => 'Crispi'],
            ['department_id' => 7, 'name' => 'Las Petacas'],
            ['department_id' => 7, 'name' => 'San Jorge'],
            ['department_id' => 7, 'name' => 'San Martín de las Escobas'],
            ['department_id' => 7, 'name' => 'Sastre'],
            ['department_id' => 7, 'name' => 'Traill'],
            ['department_id' => 8, 'name' => 'Cayastá'],
            ['department_id' => 8, 'name' => 'Colonia Mascías'],
            ['department_id' => 8, 'name' => 'Helvecia'],
            ['department_id' => 8, 'name' => 'Saladero Mariano Cabal'],
            ['department_id' => 8, 'name' => 'Santa Rosa de Calchines'],
            ['department_id' => 9, 'name' => 'Arroyo Aguiar'],
            ['department_id' => 9, 'name' => 'Arroyo Leyes'],
            ['department_id' => 9, 'name' => 'Cabal'],
            ['department_id' => 9, 'name' => 'Campo Andino'],
            ['department_id' => 9, 'name' => 'Candioti'],
            ['department_id' => 9, 'name' => 'Emilia'],
            ['department_id' => 9, 'name' => 'Laguna Paiva'],
            ['department_id' => 9, 'name' => 'Llambi Campbell'],
            ['department_id' => 9, 'name' => 'Monte Vera'],
            ['department_id' => 9, 'name' => 'Nelson'],
            ['department_id' => 9, 'name' => 'Recreo'],
            ['department_id' => 9, 'name' => 'San José del Rincón'],
            ['department_id' => 9, 'name' => 'Santa Fe'],
            ['department_id' => 9, 'name' => 'Santo Tomé'],
            ['department_id' => 9, 'name' => 'Sauce Viejo'],
            ['department_id' => 10, 'name' => 'Cavour (Colonia)'],
            ['department_id' => 10, 'name' => 'Cululú'],
            ['department_id' => 10, 'name' => 'Elisa'],
            ['department_id' => 10, 'name' => 'Empalme San Carlos'],
            ['department_id' => 10, 'name' => 'Esperanza'],
            ['department_id' => 10, 'name' => 'Felicia'],
            ['department_id' => 10, 'name' => 'Franck'],
            ['department_id' => 10, 'name' => 'Grutly'],
            ['department_id' => 10, 'name' => 'Hipatia'],
            ['department_id' => 10, 'name' => 'Humboldt'],
            ['department_id' => 10, 'name' => 'Ituzaingó'],
            ['department_id' => 10, 'name' => 'Jacinto L. Arauz'],
            ['department_id' => 10, 'name' => 'La Pelada'],
            ['department_id' => 10, 'name' => 'Las Tunas'],
            ['department_id' => 10, 'name' => 'María Luisa'],
            ['department_id' => 10, 'name' => 'Matilde'],
            ['department_id' => 10, 'name' => 'Nuevo Torino'],
            ['department_id' => 10, 'name' => 'Pilar'],
            ['department_id' => 10, 'name' => 'Progreso'],
            ['department_id' => 10, 'name' => 'Providencia'],
            ['department_id' => 10, 'name' => 'Pujato Norte'],
            ['department_id' => 10, 'name' => 'Rivadavia'],
            ['department_id' => 10, 'name' => 'Sa Pereira'],
            ['department_id' => 10, 'name' => 'San Agustín'],
            ['department_id' => 10, 'name' => 'San Carlos Centro'],
            ['department_id' => 10, 'name' => 'San Carlos Norte'],
            ['department_id' => 10, 'name' => 'San Carlos Sud'],
            ['department_id' => 10, 'name' => 'San Jerónimo del Sauce'],
            ['department_id' => 10, 'name' => 'San Jerónimo Norte'],
            ['department_id' => 10, 'name' => 'San José (Colonia)'],
            ['department_id' => 10, 'name' => 'San Mariano'],
            ['department_id' => 10, 'name' => 'Santa Clara de Buena Vista'],
            ['department_id' => 10, 'name' => 'Santa María Centro'],
            ['department_id' => 10, 'name' => 'Santa María Norte'],
            ['department_id' => 10, 'name' => 'Santo Domingo'],
            ['department_id' => 10, 'name' => 'Sarmiento'],
            ['department_id' => 10, 'name' => 'Soutomayor'],
            ['department_id' => 11, 'name' => 'Cacique Ariacaiquín'],
            ['department_id' => 11, 'name' => 'Colonia Teresa'],
            ['department_id' => 11, 'name' => 'La Brava'],
            ['department_id' => 11, 'name' => 'San Javier'],
            ['department_id' => 12, 'name' => 'Arocena'],
            ['department_id' => 12, 'name' => 'Barrancas'],
            ['department_id' => 12, 'name' => 'Bernardo de Irigoyen'],
            ['department_id' => 12, 'name' => 'Campo Piaggio'],
            ['department_id' => 12, 'name' => 'Casalegno'],
            ['department_id' => 12, 'name' => 'Coronda'],
            ['department_id' => 12, 'name' => 'Desvío Arijón'],
            ['department_id' => 12, 'name' => 'Díaz'],
            ['department_id' => 12, 'name' => 'Gaboto'],
            ['department_id' => 12, 'name' => 'Gálvez'],
            ['department_id' => 12, 'name' => 'Gessler'],
            ['department_id' => 12, 'name' => 'Irigoyen (Pueblo)'],
            ['department_id' => 12, 'name' => 'Larrechea'],
            ['department_id' => 12, 'name' => 'Loma Alta (Villa Tramontina)'],
            ['department_id' => 12, 'name' => 'López'],
            ['department_id' => 12, 'name' => 'Maciel'],
            ['department_id' => 12, 'name' => 'Monje'],
            ['department_id' => 12, 'name' => 'San Eugenio'],
            ['department_id' => 12, 'name' => 'San Fabián'],
            ['department_id' => 13, 'name' => 'Angeloni'],
            ['department_id' => 13, 'name' => 'Cayastacito'],
            ['department_id' => 13, 'name' => 'Colonia Dolores'],
            ['department_id' => 13, 'name' => 'Colonia Silva'],
            ['department_id' => 13, 'name' => 'Esther (Colonia)'],
            ['department_id' => 13, 'name' => 'Gobernador Crespo'],
            ['department_id' => 13, 'name' => 'La Camila'],
            ['department_id' => 13, 'name' => 'La Criolla'],
            ['department_id' => 13, 'name' => 'La Penca y Caraguatá'],
            ['department_id' => 13, 'name' => 'Marcelino Escalada'],
            ['department_id' => 13, 'name' => 'Naré'],
            ['department_id' => 13, 'name' => 'Pedro Gómez Cello'],
            ['department_id' => 13, 'name' => 'Ramayón'],
            ['department_id' => 13, 'name' => 'San Bernardo (Pueblo)'],
            ['department_id' => 13, 'name' => 'San Justo'],
            ['department_id' => 13, 'name' => 'San Martín Norte'],
            ['department_id' => 13, 'name' => 'Vera y Pintado'],
            ['department_id' => 13, 'name' => 'Videla'],
            ['department_id' => 14, 'name' => 'Armstrong'],
            ['department_id' => 14, 'name' => 'Bouquet'],
            ['department_id' => 14, 'name' => 'Las Parejas'],
            ['department_id' => 14, 'name' => 'Las Rosas'],
            ['department_id' => 14, 'name' => 'Montes de Oca'],
            ['department_id' => 14, 'name' => 'Tortugas'],
            ['department_id' => 15, 'name' => 'Arequito'],
            ['department_id' => 15, 'name' => 'Arteaga'],
            ['department_id' => 15, 'name' => 'Bigand'],
            ['department_id' => 15, 'name' => 'Casilda'],
            ['department_id' => 15, 'name' => 'Chabás'],
            ['department_id' => 15, 'name' => 'Los Molinos'],
            ['department_id' => 15, 'name' => 'San José de la Esquina'],
            ['department_id' => 15, 'name' => 'Sanford'],
            ['department_id' => 15, 'name' => 'Villada'],
            ['department_id' => 16, 'name' => 'Alcorta'],
            ['department_id' => 16, 'name' => 'Cañada Rica'],
            ['department_id' => 16, 'name' => 'Cepeda'],
            ['department_id' => 16, 'name' => 'Empalme Villa Constitución'],
            ['department_id' => 16, 'name' => 'General Gelly'],
            ['department_id' => 16, 'name' => 'Godoy'],
            ['department_id' => 16, 'name' => 'Juan B. Molina'],
            ['department_id' => 16, 'name' => 'Juncal'],
            ['department_id' => 16, 'name' => 'La Vanguardia'],
            ['department_id' => 16, 'name' => 'Máximo Paz'],
            ['department_id' => 16, 'name' => 'Pavón'],
            ['department_id' => 16, 'name' => 'Pavón Arriba'],
            ['department_id' => 16, 'name' => 'Peyrano'],
            ['department_id' => 16, 'name' => 'Rueda'],
            ['department_id' => 16, 'name' => 'Santa Teresa'],
            ['department_id' => 16, 'name' => 'Sargento Cabral'],
            ['department_id' => 16, 'name' => 'Theobald'],
            ['department_id' => 16, 'name' => 'Villa Constitución'],
            ['department_id' => 17, 'name' => 'Andino (Pueblo)'],
            ['department_id' => 17, 'name' => 'Bustinza'],
            ['department_id' => 17, 'name' => 'Cañada de Gómez'],
            ['department_id' => 17, 'name' => 'Carrizales'],
            ['department_id' => 17, 'name' => 'Clason'],
            ['department_id' => 17, 'name' => 'Correa'],
            ['department_id' => 17, 'name' => 'Lucio V. López'],
            ['department_id' => 17, 'name' => 'Oliveros'],
            ['department_id' => 17, 'name' => 'Salto Grande'],
            ['department_id' => 17, 'name' => 'Serodino'],
            ['department_id' => 17, 'name' => 'Totoras'],
            ['department_id' => 17, 'name' => 'Villa Eloísa'],
            ['department_id' => 18, 'name' => 'Acebal'],
            ['department_id' => 18, 'name' => 'Albarellos'],
            ['department_id' => 18, 'name' => 'Alvarez'],
            ['department_id' => 18, 'name' => 'Alvear'],
            ['department_id' => 18, 'name' => 'Arminda'],
            ['department_id' => 18, 'name' => 'Arroyo Seco'],
            ['department_id' => 18, 'name' => 'Carmen del Sauce'],
            ['department_id' => 18, 'name' => 'Coronel Bogado'],
            ['department_id' => 18, 'name' => 'Coronel Domínguez'],
            ['department_id' => 18, 'name' => 'Fighiera'],
            ['department_id' => 18, 'name' => 'Funes'],
            ['department_id' => 18, 'name' => 'General Lagos'],
            ['department_id' => 18, 'name' => 'Granadero Baigorria'],
            ['department_id' => 18, 'name' => 'Ibarlucea'],
            ['department_id' => 18, 'name' => 'Pérez'],
            ['department_id' => 18, 'name' => 'Piñero'],
            ['department_id' => 18, 'name' => 'Pueblo Esther'],
            ['department_id' => 18, 'name' => 'Pueblo Muñoz'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Centro)'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Norte)'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Noroeste)'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Oeste)'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Sudoeste)'],
            ['department_id' => 18, 'name' => 'Rosario (Distrito Sur)'],
            ['department_id' => 18, 'name' => 'Soldini'],
            ['department_id' => 18, 'name' => 'Uranga'],
            ['department_id' => 18, 'name' => 'Villa Amelia'],
            ['department_id' => 18, 'name' => 'Villa Gobernador Gálvez'],
            ['department_id' => 18, 'name' => 'Zavalla'],
            ['department_id' => 19, 'name' => 'Centeno'],
            ['department_id' => 19, 'name' => 'San Genaro'],
            ['department_id' => 20, 'name' => 'Aldao'],
            ['department_id' => 20, 'name' => 'Capitán Bermúdez'],
            ['department_id' => 20, 'name' => 'Carcarañá'],
            ['department_id' => 20, 'name' => 'Coronel Arnold'],
            ['department_id' => 20, 'name' => 'Fray Luis Beltrán'],
            ['department_id' => 20, 'name' => 'Fuentes'],
            ['department_id' => 20, 'name' => 'Luis Palacios'],
            ['department_id' => 20, 'name' => 'Puerto General San Martín'],
            ['department_id' => 20, 'name' => 'Pujato'],
            ['department_id' => 20, 'name' => 'Ricardone'],
            ['department_id' => 20, 'name' => 'Roldán'],
            ['department_id' => 20, 'name' => 'San Jerónimo Sud'],
            ['department_id' => 20, 'name' => 'San Lorenzo'],
            ['department_id' => 20, 'name' => 'Timbúes'],
            ['department_id' => 20, 'name' => 'Villa Mugueta'],
            ['department_id' => 21, 'name' => 'Cañada Rosquín'],
            ['department_id' => 21, 'name' => 'Carlos Pellegrini'],
            ['department_id' => 21, 'name' => 'Casas'],
            ['department_id' => 21, 'name' => 'Colonia Belgrano'],
            ['department_id' => 21, 'name' => 'El Trébol'],
            ['department_id' => 21, 'name' => 'Landeta'],
            ['department_id' => 21, 'name' => 'Las Bandurrias'],
            ['department_id' => 21, 'name' => 'Los Cardos'],
            ['department_id' => 21, 'name' => 'María Susana'],
            ['department_id' => 21, 'name' => 'Piamonte'],
            ['department_id' => 22, 'name' => 'Berabevú'],
            ['department_id' => 22, 'name' => 'Chañar Ladeado'],
            ['department_id' => 22, 'name' => 'Gödekén'],
            ['department_id' => 22, 'name' => 'Los Quirquinchos'],
            ['department_id' => 23, 'name' => 'Bombal'],
            ['department_id' => 24, 'name' => 'Aarón Castellanos'],
            ['department_id' => 24, 'name' => 'Amenábar'],
            ['department_id' => 24, 'name' => 'Cafferata'],
            ['department_id' => 24, 'name' => 'Cañada del Ucle'],
            ['department_id' => 24, 'name' => 'Carmen'],
            ['department_id' => 24, 'name' => 'Carreras'],
            ['department_id' => 24, 'name' => 'Chapuy'],
            ['department_id' => 24, 'name' => 'Chovet'],
            ['department_id' => 24, 'name' => 'Christophersen (Colonia)'],
            ['department_id' => 24, 'name' => 'Diego de Alvear'],
            ['department_id' => 24, 'name' => 'Elortondo'],
            ['department_id' => 24, 'name' => 'Firmat'],
            ['department_id' => 24, 'name' => 'Hughes'],
            ['department_id' => 24, 'name' => 'La Chispa'],
            ['department_id' => 24, 'name' => 'Labordeboy'],
            ['department_id' => 24, 'name' => 'Lazzarino'],
            ['department_id' => 24, 'name' => 'Maggiolo'],
            ['department_id' => 24, 'name' => 'María Teresa'],
            ['department_id' => 24, 'name' => 'Melincué'],
            ['department_id' => 24, 'name' => 'Miguel Torres (Pueblo)'],
            ['department_id' => 24, 'name' => 'Murphy'],
            ['department_id' => 24, 'name' => 'Rufino'],
            ['department_id' => 24, 'name' => 'San Eduardo'],
            ['department_id' => 24, 'name' => 'San Francisco de Santa Fe'],
            ['department_id' => 24, 'name' => 'San Gregorio'],
            ['department_id' => 24, 'name' => 'Sancti Spíritu'],
            ['department_id' => 24, 'name' => 'Santa Isabel'],
            ['department_id' => 24, 'name' => 'Teodelina'],
            ['department_id' => 24, 'name' => 'Venado Tuerto'],
            ['department_id' => 24, 'name' => 'Villa Cañás'],
            ['department_id' => 24, 'name' => 'Wheelwright'],
        ]);
        $this->db->table('localities')->insert([
            ['department_id' => 1, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 2, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 3, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 4, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 5, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 6, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 7, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 8, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 9, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 10, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 11, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 12, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 13, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 14, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 15, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 16, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 17, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 18, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 19, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 20, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 21, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 22, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 23, 'name' => 'Otra ...', 'custom' => true],
            ['department_id' => 24, 'name' => 'Otra ...', 'custom' => true],
        ]);
    }
}
