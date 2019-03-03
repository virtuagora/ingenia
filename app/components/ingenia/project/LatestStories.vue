<template>
  <section>
    <div class="media">
      <div class="media-left is-hidden-touch">
        <img src="/assets/img/historia-small.svg" class="logo-header image is-centered" style="width:100px">
      </div>
      <div class="media-content has-text-right-desktop has-text-centered-touch" style="overflow: visible">
        <img src="/assets/img/historia-small.svg" class="logo-header image is-centered is-hidden-desktop" style="width:100px">
        <h1 class="title is-size-2-desktop is-size-3-touch is-600">Toda historia nace en equipo</h1>
        <h1 class="subtitle is-5 is-size-6-touch">Acercate a sus historias y conocelos personalmente</h1>
      </div>
    </div>
    <br>
    <!-- Main container -->
    <nav class="level">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5" >
            Estas viendo las ultimas 4 historias
          </p>
        </div>
      </div>
      <!-- Right side -->
      <div class="level-right" v-if="user && user.groups[0] && user.groups[0].project.id == projectId">
        <p class="level-item">
          <a :href="'/grupo/'+user.groups[0].id+'/historia/nuevo'" class="button is-info is-400 is-outlined"><i class="fas fa-edit fa-fw"></i>&nbsp;Publicar una nueva historia</a>
        </p>
      </div>
    </nav>
    <div class="section has-text-centered" v-if="stories.length === 0">
      <i class="fas fa-info-circle"></i> No han subido ninguna historia (¡Aún!)
    </div>
    <div v-else>
      <div class="columns is-multiline">
        <div class="column is-3" v-for="story in stories" :key="story.id">
          <a :href="'/historia/' + story.id">
            <img :src="'/stories/images/' + story.id" alt="">
          </a>
        </div>
      </div>
    </div>
    <br>
    <div class="buttons is-centered">
      <router-link :to="{name: 'projectHistorias'}" class="button is-medium is-primary is-outlined">Mirá todas las historias</router-link>
    </div>
  </section>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";

export default {
  props: [
    "projectId",
    "isAdmin",
    "isCoordinator",
  ],
  data() {
    return {
      stories: [],
      isLoading: false,
      sendingstory: false,
      response: {
        ok: false
      },
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      }
    };
  },
  components: {
    InfiniteLoading
  },
  mounted: function(){
    this.getstories()
  },
  methods: {
    getstories: function() {
      this.isLoading = true;
      this.$http
        .get(this.storiesUrl)
        .then(response => {
          // this.stories = this.stories.concat(response.data.data);
          this.stories = response.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error al obtener los historias. Recargue la página",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    updatestories: function() {
      this.resetEverything();
    },
  },
  computed: {
    storiesUrl: function(){
      return '/project/' + this.projectId + '/stories?latest=true&size=4'
    },
    user: function(){
      return this.$store.state.user
    }
  }
};
</script>

<style>
</style>
