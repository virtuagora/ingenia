<template>
  <div class="hero-foot">
        <nav class="tabs is-boxed">
          <div class="container">
            <ul>
              <li :class="{'is-active': $route.name == 'projectOverview' }">
                <router-link :to="{ name: 'projectOverview'}">Acerca</router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectTeam' }">
                <router-link :to="{ name: 'projectTeam'}">&nbsp;<i class="fas fa-users fa-lg"></i>&nbsp;<span class="is-hidden-touch">&nbsp;Equipo</span></router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectImplementation' }">
                <router-link :to="{ name: 'projectImplementation'}">&nbsp;<i class="fas fa-file fa-lg"></i>&nbsp;<span class="is-hidden-touch">&nbsp;Proyecto</span></router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectHistorias' }">
                <router-link :to="{ name: 'projectHistorias'}">&nbsp;<i class="fas fa-camera-retro fa-lg"></i>&nbsp;<span class="is-hidden-touch">&nbsp;Historias</span></router-link>
              </li>
              <li>
                <a @click="scrollMeTo('comentarios')"><i class="fas fa-comments fa-lg"></i><span class="is-hidden-touch">&nbsp;&nbsp;Comentarios</span></a>
              </li>
              <li><a href="/"><span class="icon"><i class="fas fa-home fa-lg"></i></span></a></li>
            </ul>
          </div>
        </nav>
      </div>
</template>

<script>
export default {
methods:{
  scrollMeTo(id) {
    var element = document.getElementById(id);
    var top = element.offsetTop;

    window.scrollTo(0, top);
  }
}
}
</script>

<style>

</style>
