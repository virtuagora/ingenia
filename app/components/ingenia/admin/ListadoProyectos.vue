<template>
  <section>
    <h1 class="subtitle is-3">Listado de proyectos</h1>
    <!-- Main container -->
    <div class="box">

    <nav class="level projects-filter">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <div class="field has-addons" style="flex-grow: 1">
            <p class="control is-expanded has-icons-left">
              <input
                v-model="nameToSearch"
                class="input"
                type="text"
                placeholder="Proyecto y/o equipo"
              >
              <span class="icon is-left">
                <i class="fas fa-chevron-right fa-lg"></i>
              </span>
            </p>
            <p class="control">
              <button @click="search()" class="button is-white is-600">Buscar</button>
            </p>
          </div>
        </div>
        <div class="level-item">
          <a @click="onlySelected = !onlySelected" class="button is-white" :class="{'is-warning': onlySelected}">
            <i class="fas fa-trophy fa-fw"></i>
          </a>
        </div>
      </div>
      <!-- Right side -->
      <div class="level-right" v-if="!filters">
        <div class="level-item">
          <a @click="showFilters()" class="button is-white">
            <i class="fas fa-filter fa-fw"></i>&nbsp;Otros filtros
          </a>
        </div>
      </div>
      <div class="level-right" v-else>
        <div class="level-item">
          <b-field expanded style="flex-grow: 1">
            <b-select
              v-model="categoriaSelected"
              :loading="categoriasLoading"
              placeholder="Categoria"
              expanded
            >
              <option :value="null">Todas</option>
              <option
                v-for="categoria in categorias"
                :key="categoria.id"
                :value="categoria.id"
              >{{categoria.name}}</option>
            </b-select>
          </b-field>
        </div>
        <div class="level-item">
          <b-field expanded style="flex-grow: 1">
            <b-select
              v-model="regionSelected"
              placeholder="Región"
              :disabled="regiones.length == 0"
              :loading="regionLoading"
              expanded
            >
              <option v-for="region in regiones" :key="region.id" :value="region">{{region.name}}</option>
            </b-select>
          </b-field>
        </div>
        <div class="level-item" v-if="regionSelected !== null">
          <b-field expanded style="flex-grow: 1">
            <b-select
              v-model="departamentoSelected"
              placeholder="Departamento"
              :disabled="departamentos.length == 0"
              :loading="departamentoLoading"
              expanded
            >
              <option
                v-for="departamento in departamentos"
                :key="departamento.id"
                :value="departamento"
              >{{departamento.name}}</option>
            </b-select>
          </b-field>
        </div>
        <div class="level-item" v-if="departamentoSelected !== null">
          <b-field expanded style="flex-grow: 1">
            <b-select
              v-model="localidadSelected"
              placeholder="Localidad"
              :disabled="localidades.length == 0"
              :loading="localidadLoading"
              expanded
            >
              <option
                v-for="localidad in localidades"
                :key="localidad.id"
                :value="localidad"
              >{{localidad.name}}</option>
            </b-select>
          </b-field>
        </div>
        <div class="level-item">
          <b-field>
            <a @click="search()" class="button is-white">
              <i class="fas fa-search fa-fw"></i>
              <span class="is-hidden-desktop">&nbsp;Buscar</span>
            </a>
          </b-field>
        </div>
        <div class="level-item">
          <b-field>
            <a @click="cleanFilters()" class="button is-white">
              <i class="fas fa-eraser fa-fw"></i>&nbsp;Borrar
              <span class="is-hidden-desktop">&nbsp;filtros</span>
            </a>
          </b-field>
        </div>
      </div>
    </nav>
    </div>
    <!-- <b-table
      :data="projects"
      ref="table"
      paginated
      per-page="100"
      detailed
      detail-key="id"
      :show-detail-icon="true"
    > -->
    <b-table
      :data="projects"
      ref="table"
    >
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>{{ props.row.id }}</b-table-column>
        <b-table-column field="name" label="Proyecto">
          <p class="is-size-5"><b><a @click="cardProyecto(props.row)">{{props.row.name}}</a></b></p>
          <p class="is-size-6"><a @click="cardEquipo(props.row.group)">{{props.row.group.name}}</a></p>
          <p class="is-size-7" v-if="props.row.notes != null">
            <i
                class="fas fa-exclamation-circle"
              ></i>&nbsp;&nbsp;Hay observaciones
            </p>
            </b-table-column>
        <b-table-column label="Acerca">
          <span class="tag">{{getCategory(props.row.category_id)}}</span>
          <p class="is-size-7">{{props.row.likes}} me gusta</p>
          <p class="is-size-7" v-if="props.row.group.quota != null"><b>Puntaje:</b>&nbsp;&nbsp;&nbsp;{{props.row.group.quota}}</p>
        </b-table-column>
        <b-table-column field="granted_budget" label="Monto" width="100">
          <p class="is-size-7"><b>Solicitado</b><br>AR$ {{getTotalBudget(props.row)}}</p>
          <p class="is-size-7"><b>Otorgado</b><br>AR$ {{props.row.granted_budget}}</p>
        </b-table-column>
        <b-table-column field="granted_budget" label="Info">
          <p class="is-size-7 has-text-primary is-700" v-if="props.row.selected == true && props.row.group.quota != null" ><i class="fas fa-trophy"></i>&nbsp;&nbsp;Proyecto seleccionado</p>
          <p class="is-size-7" v-if="props.row.has_coordins == null"><i class="fas fa-times"></i>&nbsp;&nbsp;Falta asignar coordinador</p>
          <p class="is-size-7" v-if="props.row.selected == false && props.row.group.quota == null"><i class="fas fa-times"></i>&nbsp;&nbsp;Proyecto no evaluado</p>
          <p class="is-size-7" v-if="props.row.group.quota != null"><i class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;Puntaje: {{ props.row.group.quota}}</p>
          <p class="is-size-7" v-if="props.row.selected == false && props.row.group.quota != null"><i class="fas fa-times"></i>&nbsp;&nbsp;Proyecto no seleccionado</p>
          <p class="is-size-7" v-if="props.row.group.upload_agreement == false"><i class="fas fa-times"></i>&nbsp;&nbsp;Falta la carta de conformidad</p>
          <p class="is-size-7" v-if="props.row.organization != null && props.row.group.uploaded_letter == false"><i class="fas fa-times"></i>&nbsp;&nbsp;Falta la carta de aval</p>
          <p class="is-size-7" v-if="props.row.group.full_team == false"><i class="fas fa-times"></i>&nbsp;&nbsp;Aun no cumple el cupo mínimo</p>
          <p class="is-size-7" v-if="props.row.group.second_in_charge == false"><i class="fas fa-times"></i>&nbsp;&nbsp;Falta asignar co-responsable</p>
          <p class="is-size-7" v-if="props.row.group.verified_team == false"><i class="fas fa-times"></i>&nbsp;&nbsp;Faltan DNIs por verificar</p>
          <p class="is-size-7" v-if="props.row.selected == true && props.row.budget_sent == 0"><i class="fas fa-times"></i>&nbsp;&nbsp;Falta que envien la rendición</p>
          <p class="is-size-7 has-text-danger is-700" v-if="props.row.selected == true && props.row.budget_sent == 1 && props.row.budget_approved == 0"><i class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;Falta APROBAR la rendición</p>
          <p class="is-size-7 has-text-success is-700" v-if="props.row.selected == true && props.row.budget_sent == 1 && props.row.budget_approved == 1"><i class="fas fa-check"></i>&nbsp;&nbsp;Rendicion aprobada</p>
        </b-table-column>
        <b-table-column label="Acciones">
          <p class="is-size-7"><a :href="'/project/'+props.row.id+'/print'" target="_blank">
                <i class="fas fa-print"></i>&nbsp;Imprimir
              </a></p>
          <p class="is-size-7" v-if="props.row.has_coordins"><a
                @click="cardEvaluar(props.row)"
              ><i class="fas fa-file-signature"></i>&nbsp;Evaluar</a></p>
          <p class="is-size-7" v-else>
              <i class="fas fa-times"></i>&nbsp;No puede evaluar sin un coordinador</p>
          <p class="is-size-7" v-if="(props.row.organization != null) && props.row.group.uploaded_letter"><a
                :href="letterUrl(props.row.group)"
                class="has-text-link"
                target="_blank"
              >
                <i class="fas fa-download"></i>&nbsp;Descargar carta aval</a></p>
          <p class="is-size-7" v-if="props.row.group.uploaded_agreement"><a
                :href="agreementUrl(props.row.group)"
                class="has-text-link"
                target="_blank"
              >
                <i class="fas fa-download"></i>&nbsp;Descargar carta conformidad</a></p>
          <p class="is-size-7" v-if="props.row.selected">
                <a :href="'/admin/project/'+props.row.id+'/receipts'" target="_blank" class="has-text-link">
                <i class="fas fa-dollar-sign"></i>&nbsp;Ver rendicion de gastos
              </a></p>
        </b-table-column>
      </template>

      <!-- <template slot="detail" slot-scope="props">
        <nav class="level">
          <div class="level-item has-text-centered">
            <div>
              <p class="heading">Cupo mínimo</p>
              <p class="title">
                <i class="fas fa-fw" :class="statusTeam(props.row)"></i>
              </p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading">Co-responsable</p>
              <p class="title">
                <i class="fas fa-fw" :class="statusSecond(props.row)"></i>
              </p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading">DNIs verificados</p>
              <p class="title">
                <i class="fas fa-fw" :class="statusVerifiedTeam(props.row)"></i>
              </p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading">Carta Conform.</p>
              <p class="title">
                <i class="fas fa-fw" :class="statusAgreement(props.row)"></i>
                <a
                  :href="agreementUrl(props.row.group)"
                  class="has-text-link"
                  target="_blank"
                  v-if="props.row.group.uploaded_agreement"
                >
                  <i class="fas fa-download"></i>
                </a>
              </p>
            </div>
          </div>
        </nav>
      </template> -->
    </b-table>

    <!-- <div class="content is-small">
      <table class="table is-fullwidth">
        <thead>
          <tr>
            <th class="has-text-centered">
              <i class="fa fa-hashtag"></i>
            </th>
            <th class="has-text-centered">
              <i class="fa fa-sticky-note"></i>
            </th>
            <th class="has-text-centered">
              <i class="fa fa-trophy"></i>
            </th>
            <th>Nombre</th>
            <th>Equipo</th>
            <th>Categoria</th>
            <th class="has-text-centered">
              <b-tooltip label="Puntaje" type="is-dark" position="is-top">
                <i class="fas fa-check-square"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Monto otorgado" type="is-dark" position="is-top">
                <i class="fas fa-dollar-sign"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Cuantos bancan el proyecto?" type="is-dark" position="is-top">
                <i class="em em-muscle"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Cupo minimo" type="is-dark" position="is-top">
                <i class="fas fa-users fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Asignado Co-responsable" type="is-dark" position="is-top">
                <i class="fas fa-shield-alt fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="DNIs del equipo verificado" type="is-dark" position="is-top">
                <i class="fas fa-check fa-fw"></i>
                <i class="fas fa-address-card fa-lg"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Carta conformidad" type="is-dark" position="is-top">
                <i class="far fa-address-book fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Carta aval" type="is-dark" position="is-top">
                <i class="fas fa-file-pdf fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Rendición enviado" type="is-dark" position="is-top">
                <i class="fas fa-dollar-sign fa-lg"></i>&nbsp;
                <i class="fas fa-paper-plane"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Rendición aprobado" type="is-dark" position="is-top">
                <i class="fas fa-dollar-sign fa-lg"></i>&nbsp;
                <i class="fas fa-check"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Recibos" type="is-dark" position="is-top">
                <i class="fas fa-archive fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Imprimir" type="is-dark" position="is-top">
                <i class="fas fa-print fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
            <th class="has-text-centered">
              <b-tooltip label="Evaluar" type="is-dark" position="is-top">
                <i class="fas fa-arrow-down fa-lg fa-fw"></i>
              </b-tooltip>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in projects" :key="project.id">
            <td class="has-text-centered">{{project.id}}</td>
            <td class="has-text-centered">
              <i
                class="fas fa-fw"
                :class="{'fa-sticky-note has-text-link': project.notes != null, 'fa-minus': project.notes == null}"
              ></i>
            </td>
            <td class="has-text-centered">
              <i
                class="fas fa-fw"
                :class="{'fa-trophy has-text-warning': project.selected, 'fa-minus': !project.selected}"
              ></i>
            </td>
            <td>
              <a @click="cardProyecto(project)">{{project.name}}</a>
            </td>
            <td>
              <a @click="cardEquipo(project.group)">{{project.group.name}}</a>
            </td>
            <td>
              <span class="tag is-small is-dark">{{getCategory(project.category_id)}}</span>
            </td>
            <td class="has-text-centered">{{project.group.quota}}</td>
            <td class="has-text-centered">{{project.granted_budget}}</td>
            <td class="has-text-centered">{{project.likes}}</td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusTeam(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusSecond(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusVerifiedTeam(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusAgreement(project)"></i>
              <a
                :href="agreementUrl(project.group)"
                class="has-text-link"
                target="_blank"
                v-if="project.group.uploaded_agreement"
              >
                <i class="fas fa-download"></i>
              </a>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusLetter(project)"></i>
              <a
                :href="letterUrl(project.group)"
                class="has-text-link"
                target="_blank"
                v-if="(project.organization != null) && project.group.uploaded_letter"
              >
                <i class="fas fa-download"></i>
              </a>
            </td>
            <td class="has-text-centered">
              <i v-if="project.budget_sent" class="fas fa-check has-text-success"></i>
              <i v-else class="fas fa-times has-text-danger"></i>
            </td>
            <td class="has-text-centered">
              <i v-if="project.budget_approved" class="fas fa-check has-text-success"></i>
              <i v-else class="fas fa-question-circle has-text-dark"></i>
            </td>
            <td class="has-text-centered">
              <a
                :href="'/admin/project/'+project.id+'/receipts'"
                target="_blank"
                class="has-text-link"
              >
                <i class="fas fa-reply fa-fw"></i>
              </a>
            </td>
            <td class="has-text-centered">
              <a :href="'/project/'+project.id+'/print'" target="_blank" class="has-text-link">
                <i class="fas fa-print fa-fw"></i>
              </a>
            </td>
            <td class="has-text-centered">
              <a
                @click="cardEvaluar(project)"
                class="button is-small is-outlined is-400 is-link"
              >Evaluar</a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="19">
              <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
                <span slot="no-results">
                  <i class="fas fa-info-circle"></i> Fin de los resultados
                </span>
                <span slot="no-more">
                  <i class="fas fa-info-circle"></i> ¡Fín de la lista!
                </span>
              </infinite-loading>
            </th>
          </tr>
        </tfoot>
      </table>
    </div> -->
    <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
      <span slot="no-results">
        <i class="fas fa-info-circle"></i> Fin de los resultados
      </span>
      <span slot="no-more">
        <i class="fas fa-info-circle"></i> ¡Fín de la lista!
      </span>
    </infinite-loading>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import ModalProyecto from "./ModalProyecto";
import ModalEquipo from "./ModalEquipo";
import InfiniteLoading from "vue-infinite-loading";
import ModalReview from "../utils/ModalReview";

export default {
  props: [
    "getProjects",
    "roles",
    "getGroupMembers",
    "getLetter",
    "getAgreement",
    "putProjectNote",
    "updateReview"
  ],
  components: {
    InfiniteLoading
  },
  data() {
    return {
      isLoading: false,
      projects: [],
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      },
      regionLoading: false,
      regionSelected: null,
      departamentoLoading: false,
      departamentoSelected: null,
      localidadLoading: false,
      localidadSelected: null,
      categoriasLoading: false,
      categoriaSelected: null,
      categorias: [],
      regiones: [],
      departamentos: [],
      localidades: [],
      nameToSearch: "",
      filters: false,
      onlySelected: false
    };
  },
  mounted: function() {
    this.isLoading = true;
    this.categoriasLoading = true;
    this.regionLoading = true;
    Promise.all([this.$http.get("/category"), this.$http.get("/region")])
      .then(response => {
        this.categorias = response[0].data;
        this.categoriasLoading = false;
        this.regiones = response[1].data;
        this.regionLoading = false;
        this.isLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        this.regionLoading = false;
        this.categoriasLoading = false;
      });
  },
  methods: {
    letterUrl: function(gro) {
      return this.getLetter.replace(":gro", gro.id);
    },
    agreementUrl: function(gro) {
      return this.getAgreement.replace(":gro", gro.id);
    },
    getCategory(id) {
      let caty = this.categorias.find(x => {
        return x.id === id;
      });
      return caty ? caty.name : "";
    },
    showFilters() {
      this.filters = true;
    },
    cleanFilters: function() {
      this.filters = false;
      this.regionSelected = null;
      this.departamentoSelected = null;
      this.localidadSelected = null;
      this.categoriaSelected = null;
      this.departamentos = [];
      this.localidades = [];
      this.nameToSearch = "";
      this.projects = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    resetEverything: function() {
      this.projects = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    search: function() {
      this.resetEverything();
    },
    zeroPad: function(num, places) {
      var zero = places - num.toString().length + 1;
      return Array(+(zero > 0 && zero)).join("0") + num;
    },
    fillPaginator: function(data) {
      this.paginator.current_page = data.current_page;
      this.paginator.last_page = data.last_page;
      this.paginator.next_page_url = data.next_page_url;
      this.paginator.prev_page_url = data.prev_page_url;
    },
    infiniteHandler: function($state) {
      if (this.paginator.current_page == null) {
        this.$http
          .get(this.urlGet)
          .then(response => {
            if (response.data.data === undefined)
              throw { message: "Error en query" };
            this.projects = this.projects.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de proyectos",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();
          });
      } else if (this.paginator.next_page_url) {
        this.$http
          .get(this.paginator.next_page_url)
          .then(response => {
            if (response.data.data === undefined)
              throw { message: "Error en query" };
            this.projects = this.projects.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de proyectos",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();
          });
      } else {
        $state.complete();
      }
    },
    statusTeam: function(pro) {
      return {
        "has-text-success": pro.group.full_team,
        "fa-check": pro.group.full_team,
        "has-text-danger": !pro.group.full_team,
        "fa-times": !pro.group.full_team
      };
    },
    statusSecond: function(pro) {
      return {
        "has-text-success": pro.group.second_in_charge,
        "fa-check": pro.group.second_in_charge,
        "has-text-danger": !pro.group.second_in_charge,
        "fa-times": !pro.group.second_in_charge
      };
    },
    statusVerifiedTeam: function(pro) {
      return {
        "has-text-success": pro.group.verified_team,
        "fa-check": pro.group.verified_team,
        "has-text-danger": !pro.group.verified_team,
        "fa-times": !pro.group.verified_team
      };
    },
    statusLetter: function(pro) {
      return {
        "has-text-success":
          (pro.organization != null) & pro.group.uploaded_letter,
        "fa-check": (pro.organization != null) & pro.group.uploaded_letter,
        "is-hidden": (pro.organization != null) & pro.group.uploaded_letter,
        "fa-times": (pro.organization != null) & !pro.group.uploaded_letter,
        "has-text-danger":
          (pro.organization != null) & !pro.group.uploaded_letter,
        "fa-minus": pro.organization == null
      };
    },
    statusAgreement: function(pro) {
      return {
        "has-text-success": pro.group.uploaded_agreement,
        "fa-check": pro.group.uploaded_agreement,
        "is-hidden": pro.group.uploaded_agreement,
        "has-text-danger": !pro.group.uploaded_agreement,
        "fa-times": !pro.group.uploaded_agreement
      };
    },
    cardProyecto: function(pro) {
      this.$modal.open({
        parent: this,
        component: ModalProyecto,
        hasModalCard: true,
        props: {
          project: pro,
          categorias: this.categorias,
          putnote: this.putProjectNote
        }
      });
    },
    cardEquipo: function(gro) {
      this.$modal.open({
        parent: this,
        component: ModalEquipo,
        hasModalCard: true,
        props: { group: gro, getGroupMembers: this.getGroupMembers }
      });
    },
    showFilters() {
      this.filters = true;
    },
    cardEvaluar: function(pro) {
      this.$modal.open({
        parent: this,
        component: ModalReview,
        hasModalCard: true,
        props: {
          project: pro,
          budget: pro.granted_budget ? pro.granted_budget : null,
          selected: pro.selected,
          quota: pro.group.quota,
          url: this.updateReview.replace(":pro", pro.id)
        }
      });
    },
    getTotalBudget: function(pro){
      let reducer = (accumulator, item) => accumulator + item.amount
      return pro.budget.reduce(reducer,0)
    },
  },
  watch: {
    onlySelected: function() {
      this.resetEverything();
    },
    regionSelected: function(newVal, oldVal) {
      if (newVal != null) {
        this.departamentoSelected = null;
        this.departamentoLoading = true;
        this.$http
          .get("/region/" + newVal.id + "/department")
          .then(response => {
            this.departamentoLoading = false;
            this.departamentos = response.data;
            this.localidades = [];
            this.localidadSelected = null;
          })
          .catch(error => {
            console.error(error.message);
            this.departamentoLoading = false;
            this.localidades = [];
            this.localidadSelected = null;
            this.$snackbar.open({
              message: "Error inesperado",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          });
      }
    },
    departamentoSelected: function(newVal, oldVal) {
      if (newVal != null) {
        this.localidadSelected = null;
        this.localidadLoading = true;
        this.$http
          .get("/department/" + newVal.id + "/locality")
          .then(response => {
            this.localidades = response.data;
            this.localidadLoading = false;
          })
          .catch(error => {
            console.error(error.message);
            this.localidadLoading = false;
            this.$snackbar.open({
              message: "Error inesperado",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          });
      }
    }
    // localidadSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.resetEverything();
    //   }
    // },
  },
  computed: {
    urlGet: function() {
      let query = [];
      query.push("size=100");
      if (this.onlySelected) {
        query.push("sel=true");
      }
      if (this.nameToSearch !== "") {
        query.push("s=" + this.nameToSearch);
      }
      if (this.categoriaSelected !== null) {
        query.push("cat=" + this.categoriaSelected);
      }
      if (this.regionSelected !== null) {
        query.push("reg=" + this.regionSelected.id);
      }
      if (this.departamentoSelected !== null) {
        query.push("dep=" + this.departamentoSelected.id);
      }
      if (this.localidadSelected !== null) {
        query.push("loc=" + this.localidadSelected.id);
      }
      return this.getProjects.concat(
        query.length > 0 ? "?" : "",
        query.join("&")
      );
    }
  }
};
</script>

<style>
</style>
