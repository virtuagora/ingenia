<template>
  <section>
    <h1 class="subtitle is-3">Resetear usuarios</h1>
    <b-message>
      <p>
        Puede <i class="fas fa-sync-alt"></i>&nbsp;&nbsp;Resetear el email o la contraseña si quiere asignarle un nuevo email al usuario (solo para los que se registraron con email)
      </p>
    </b-message>
    <div class="box">
    <nav class="level projects-filter">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <div class="field has-addons" style="flex-grow: 1">
            <p class="control is-expanded has-icons-left">
              <input v-model="nameToSearch" class="input" type="text" placeholder="Nombre y/o Apellido">
              <span class="icon is-left">
                <i class="fas fa-chevron-right fa-lg"></i>
              </span>
            </p>
          </div>
        </div>
        <div class="level-item">
          <div class="field has-addons" style="flex-grow: 1">
            <p class="control is-expanded has-icons-left">
              <input v-model="nameEquipoToSearch" class="input" type="text" placeholder="Nombre equipo">
              <span class="icon is-left">
                <i class="fas fa-chevron-right fa-lg"></i>
              </span>
            </p>
          </div>
        </div>
        <div class="level-item">
          <p class="control">
              <button @click="search()" class="button is-white is-600">
                Buscar
              </button>
            </p>
        </div>
      </div>
    </nav>
    </div>
    <div class="content">
      <table class="table is-fullwidth">
        <thead>
          <tr>
            <!-- <th width="15" class="has-text-centered">
              <i class="fa fa-hashtag"></i>
            </th> -->
            <th>Apellido y Nombre</th>
            <th>Email</th>
            <th width="90" class="has-text-centered">DNI</th>
            <th width="50" class="has-text-centered">Tipo</th>
            <th width="250" class="has-text-centered">
              <i class="fas fa-redo-alt"></i> Resetear
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <!-- <td class="has-text-centered">{{user.id}}</td> -->
            <td>
              <a :href="'/usuario/'+user.id">{{user.surnames.toUpperCase()}}, {{user.names}}</a>
            </td>
            <td class="">{{user.email || '- Sin email -'}} <b-tooltip v-if="user.pending_email" 
            :label="'Esperando a que confirme el email ' + user.pending_email"
            multilined
            size="is-small"
            position="is-bottom">
            <i class="fas fa-clock fa-fw has-text-primary"></i>
        </b-tooltip>
            </td>
            <td class="has-text-centered">{{user.dni}}</td>
            <td class="has-text-centered" v-if="user.subject.img_type === 0">
              <i class="fa fa-envelope fa-lg fa-fw"></i>
            </td>
            <td class="has-text-centered" v-else>
              <i class="fab fa-facebook fa-lg fa-fw"></i>
            </td>
            <td class="has-text-centered">
              <a @click="openResetEmail(user)" class="button is-small" v-if="user.subject.img_type === 0">
                <i class="fas fa-redo-alt"></i>&nbsp;Email</a>
              <a @click="openResetPassword(user)" class="button is-small" v-if="user.subject.img_type === 0">
                <i class="fas fa-redo-alt"></i>&nbsp;Password</a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5">
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
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";
import Localidad from "../utils/GetLocalidad";
import ModalResetEmail from "./ModalResetEmail"
import ModalResetPassword from "./ModalResetPassword"

export default {
  props: ["getUsers", "postValidateDni", "getUserDni"],
  components: {
    InfiniteLoading,
    Localidad
  },
  data() {
    return {
      isLoading: false,
      users: [],
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      },
      verifiedToggle: false,
      // regionLoading: false,
      // regionSelected: null,
      // departamentoLoading: false,
      // departamentoSelected: null,
      // localidadLoading: false,
      // localidadSelected: null,
      // categoriasLoading: false,
      // categoriaSelected: null,
      // categorias: [],
      // regiones: [],
      // departamentos: [],
      // localidades: [],
      nameToSearch: "",
      nameEquipoToSearch: "",
      blacklist: "",
      filters: false
    };
  },
  methods: {
    verificarUser: function(usr) {
      this.isLoading = true;
      this.$http
        .post(this.postValidateDni.replace(":usr", usr.id))
        .then(response => {
          usr.verificado = true
          this.$snackbar.open({
            message: usr.subject.display_name + " ha sido verificado",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    // getCategory(id) {
    //   let caty = this.categorias.find(x => {
    //     return x.id === id;
    //   });
    //   return caty ? caty.name : "";
    // },
    showFilters() {
      this.filters = true;
    },
    cleanFilters: function() {
      // this.filters = false;
      // this.regionSelected = null;
      // this.departamentoSelected = null;
      // this.localidadSelected = null;
      // this.categoriaSelected = null;
      // this.departamentos = [];
      // this.localidades = [];
      this.nameToSearch = "";
      this.nameEquipoToSearch = "";
      this.users = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    resetEverything: function() {
      this.users = [];
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
            this.users = this.users.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de usuarios",
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
            this.users = this.users.concat(response.data.data);
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
     openResetEmail: function(user) {
      this.$modal.open({
        parent: this,
        component: ModalResetEmail,
        hasModalCard: true,
        props: { user: user },
        events: {
          update: this.resetEverything()
        }
      });
    },
     openResetPassword: function(user) {
      this.$modal.open({
        parent: this,
        component: ModalResetPassword,
        hasModalCard: true,
        props: { user: user },
        events: {
          update: this.resetEverything()
        }
      });
    },
    // statusTeam: function(pro) {
    //   return {
    //     "has-text-success": pro.group.full_team,
    //     "fa-check": pro.group.full_team,
    //     "has-text-danger": !pro.group.full_team,
    //     "fa-times": !pro.group.full_team
    //   };
    // },
    // statusSecond: function(pro) {
    //   return {
    //     "has-text-success": pro.group.second_in_charge,
    //     "fa-check": pro.group.second_in_charge,
    //     "has-text-danger": !pro.group.second_in_charge,
    //     "fa-times": !pro.group.second_in_charge
    //   };
    // },
    // statusLetter: function(pro) {
    //   return {Evaluación
    //     "has-textEvaluación-success":
    //       (pro.orEvaluaciónganization != null) & pro.group.uploaded_letter,
    //     "fa-checkEvaluación": (pro.organization != null) & pro.group.uploaded_letter,
    //     "fa-timesEvaluación": (pro.organization != null) & !pro.group.uploaded_letter,
    //     "fa-minus": pro.organization == null
    //   };
    // },
    // statusAgreement: function(pro) {
    //   return {
    //     "has-text-success": pro.group.uploaded_agreement,
    //     "fa-check": pro.group.uploaded_agreement,
    //     "has-text-danger": !pro.group.uploaded_agreement,
    //     "fa-times": !pro.group.uploaded_agreement
    //   };
    // },
    // cardProyecto: function(pro) {
    //   this.$modal.open({
    //     parent: this,
    //     component: ModalProyecto,
    //     hasModalCard: true,
    //     props: { project: pro, categorias: this.categorias }
    //   });
    // },
    // cardEquipo: function(gro) {
    //   this.$modal.open({
    //     parent: this,
    //     component: ModalEquipo,
    //     hasModalCard: true,
    //     props: { group: gro, getGroupMembers: this.getGroupMembers }
    //   });
    // },
    showFilters() {
      this.filters = true;
    }
  },
  watch: {
    verifiedToggle: function(newVal) {
      this.resetEverything();
    }
    // regionSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.departamentoSelected = null;
    //     this.departamentoLoading = true;
    //     this.$http
    //       .get("/region/" + newVal.id + "/department")
    //       .then(response => {
    //         this.departamentoLoading = false;
    //         this.departamentos = response.data;
    //         this.localidades = [];
    //         this.localidadSelected = null;
    //       })
    //       .catch(error => {
    //         console.error(error.message);
    //         this.departamentoLoading = false;
    //         this.localidades = [];
    //         this.localidadSelected = null;
    //         this.$snackbar.open({
    //           message: "Error inesperado",
    //           type: "is-danger",
    //           actionText: "Cerrar"
    //         });
    //         return false;
    //       });
    //   }
    // },
    // departamentoSelected: function(newVal, oldVal) {
    //   if (newVal Evaluación!= null) {
    //     this.localidadSelected = null;
    //     this.localidadLoading = true;
    //     this.$http
    //       .get("/department/" + newVal.id + "/locality")
    //       .then(response => {
    //         this.localidades = response.data;
    //         this.localidadLoading = false;
    //       })
    //       .catch(error => {
    //         console.error(error.message);
    //         this.localidadLoading = false;
    //         this.$snackbar.open({
    //           message: "Error inesperado",
    //           type: "is-danger",
    //           actionText: "Cerrar"
    //         });
    //         return false;
    //       });
    //   }
    // }
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
      if (this.nameToSearch !== "") {
        query.push("s=" + this.nameToSearch);
      }
      if (this.nameEquipoToSearch !== "") {
        query.push("equipo=" + this.nameEquipoToSearch);
      }
      // if (this.regionSelected !== null) {
      //   query.push("reg=" + this.regionSelected.id);
      // }
      // if (this.departamentoSelected !== null) {
      //   query.push("dep=" + this.departamentoSelected.id);
      // }
      // if (this.localidadSelected !== null) {
      //   query.push("loc=" + this.localidadSelected.id);
      // }
      return this.getUsers.concat(query.length > 0 ? "?" : "", query.join("&"));
    }
  }
};
</script>