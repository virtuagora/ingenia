<template>
  <article>
    <img src="/assets/img/ingenia-logo.svg" class="image" style="max-width: 150px;">
    <br>
    <h1 class="title is-2">¡Hola {{user.names}}!</h1>
    <h1 class="subtitle is-5" v-if="user.groups[0] !== undefined">
          <i class="em em-tada"></i> Miembro del equipo <b>{{user.groups[0].name}}</b> </h1>
    <p>¡Bienvenidx a tu panel! Aquí tendrás control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <br>
    <p v-if="user.groups[0] === undefined">Previo a ser parte de un equipo o de presentar tu proyecto INGENIA, debes cumplir los siguientes requisitos.</p>
    <br>
    <status></status>
    <br>
    <countdown :date="deadline" :dateDocuments="deadlineDocuments" class="is-dark"></countdown>      
    <div class="notification is-info" v-if="user.groups[0] !== undefined && user.groups[0].project !== null">
        <i class="fas fa-eye fa-fw"></i> Tu proyecto está publico en la web, podes ingresar haciendo <b><a :href="'/proyecto/' + user.groups[0].project.id">Clic aquí</a></b>
      </div>
  </article>
</template>

<script>
import Status from "../utils/Status";
import Countdown from "../utils/Countdown";

export default {
  components: {
    Status,
    Countdown,
  },
  props: ["id","deadline", "deadlineDocuments"],
  data() {
    return {
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  computed: {
    invitacionesPendientes: function(){
      return this.user.invitations.filter(x => {
        return x.state == "pending";
      })
    }
  }
};
</script>
