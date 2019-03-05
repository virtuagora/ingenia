<template>
  <div class="hero is-small is-dark">
    <div class="hero-body">
      <div class="container">
        <section v-if="coordinators.length === 0">
          <h3 class="is-size-4">
            <b>El proyecto no tiene asignado un coordinador</b>
          </h3>
          <a @click="assign()" v-if="isCoordinator" class="button is-info"><i class="fas fa-plus"></i>&nbsp;Asignarme como coordinador</a>
        </section>
        <section v-if="coordinators.length">
          <div class="columns">
            <div
              class="column"
              :class="{'is-8': review.grantedBudget == null, 'is-6': review.grantedBudget != null}"
            >
              <h1 class="is-size-5 is-300">Coordinadores</h1>
              <article class="media" v-for="coordinator in coordinators" :key="coordinator.id">
                <Avatar :user="coordinator.id" class="media-left" size="32"></Avatar>
                <div class="media-content" style="overflow: inherit">
                  <h1 class="is-size-4 is-600">
                    {{coordinator.names}} {{coordinator.surnames}}
                  </h1>
                </div>
              </article>
            </div>
            <div
              class="column has-text-centered"
              :class="{'is-4': review.grantedBudget == null, 'is-3': review.grantedBudget != null}"
            >
              <h1 class="is-size-5 is-300">Evaluación</h1>
              <h1 class="is-size-1 is-600">{{review.quota ? review.quota : '-'}}</h1>
            </div>
            <div class="column is-3 has-text-centered" v-if=" review.grantedBudget != null">
              <h1 class="is-size-5 is-300">Monto otorgado</h1>
              <h1 class="is-size-1 is-600">$ {{review.grantedBudget}}</h1>
            </div>
          </div>
          <hr>
           <div class="buttons is-centered">
                  <a @click="assign()" v-if="isCoordinator && !this.idCoordinators.includes(user.id)" class="button is-info"><i class="fas fa-plus"></i>&nbsp;Asignarme como coordinador</a>

                    <a
                      @click="cardEvaluar()"
                      class="button is-white is-outlined"
                      v-if="(isCoordinator && this.idCoordinators.includes(user.id)) || isAdmin"
                    >Cambiar evaluación</a>
                    <a
                      @click="deleteCoord()"
                      class="button is-danger"
                      v-if="isCoordinator && this.idCoordinators.includes(user.id)"
                    >Dejar de coordinar</a>
                  </div>
        </section>
        <b-loading :active.sync="isLoading"></b-loading>
      </div>
    </div>
  </div>
</template>

<script>
import ModalReview from "../utils/ModalReview";
import Avatar from "../utils/Avatar";
export default {
  props: [
    "id",
    "isAdmin",
    "isCoordinator",
    "coordinators",
    "grantedBudget",
    "selected",
    "quota",
    "updateReview",
    "assignCoordinator",
    "deleteCoordinator"
  ],
  components: {
    ModalReview,
    Avatar
  },
  data() {
    return {
      user: {},
      isLoading: false,
      coordinator: null,
      review: {
        quota: this.quota,
        selected: this.selected,
        grantedBudget: this.grantedBudget
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
 
  },
  methods: {
    assign: function() {
      this.$http
        .post(this.assignCoordinator)
        .then(response => {
          location.reload();
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    deleteCoord: function() {
      this.$http
        .delete(this.deleteCoordinator)
        .then(response => {
          location.reload();
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    cardEvaluar: function() {
      this.$modal.open({
        parent: this,
        component: ModalReview,
        hasModalCard: true,
        props: {
          project: this.id,
          budget: this.review.grantedBudget,
          selected: this.review.selected,
          quota: this.review.quota,
          url: this.updateReview
        }
      });
    }
  },
  computed: {
    idCoordinators: function(){
      return this.coordinators.map( c => {
        return c.id
      })
    }
  }
};
</script>

<style>
</style>
