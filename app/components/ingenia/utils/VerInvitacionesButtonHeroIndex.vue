<template>
  <div v-if="showButton" class="column is-narrow">
    <b-tooltip label="Tenes invitaciones" type="is-primary" position="is-bottom">
      <a href="/panel/perfil/invitaciones">
      <span class=" icon is-large has-text-white badge is-badge-danger is-badge-medium"  data-badge="">
          <i class="fas fa-envelope fa-3x"></i>
         </span>&nbsp;&nbsp;
      </a>
    </b-tooltip>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user
    };
  },
  mounted: function() {
    this.user = this.$store.state.user;

    let intervalId = setInterval(
      function() {
        if (this.user == null) {
        console.log("Tick!");
          this.user = this.$store.state.user;
        } else {
        console.log("STOP!");          
          clearInterval( intervalId );
        }
      }.bind(this),
      2000
    );
  },
  computed: {
    invitacionesPendientes: function(){
      return this.user.invitations.filter(x => {
        return x.state == "pending";
      })
    },
    showButton: function() {
      return (
        this.user !== null &&
        this.invitacionesPendientes.length > 0
      );
    }
  }
};
</script>

<style>

</style>
