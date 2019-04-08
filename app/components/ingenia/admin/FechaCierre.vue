<template>
  <section>
    <h1 class="subtitle is-3">Cambiar fecha <u>de cierre</u> de proyectos</h1>
    <p>Aquí pueden modificar la fecha de cierre de la inscripción de los proyectos.
      <br>Una vez que se pasa esta fecha, los equipos si no subieron su proyecto, ya no podrán hacerlo.</p>
    <br>
    <b-message type="is-warning">
      <b>NOTA: Cuidar que esta fecha sea posterior o igual a la fecha limite para la documentación y los requerimientos. Para  cambiarlo, haga <router-link :to="{name:'adminFechaLimiteRequerimientos'}">clic aquí</router-link> </b>  
    </b-message>
    <b-message type="is-warning">
      Nota: Al modificar este dato, tu sesión se cerrará, tendras que volver a loguearte.
    </b-message>
    <br>
    <div class="columns">
      <div class="column">

        <div class="field">
          <label class="label is-size-4">
            <i class="fas fa-angle-double-right"></i>&nbsp;Día de cierre</label>
          <b-datepicker v-model="theDate" placeholder="Click para elegir..." icon="calendar" size="is-medium">
          </b-datepicker>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label class="label is-size-4">
            <i class="fas fa-angle-double-right"></i>&nbsp;Hora de cierre</label>
          <b-timepicker v-model="theTime" size="is-medium" placeholder="Click para elegir..." icon="clock"></b-timepicker>
        </div>
      </div>
    </div>
    <div class="buttons">
        <button @click="submit()" class="button is-link is-medium">Guardar</button>
      </div>
  </section>
</template>

<script>
export default {
  props: ["settings"],
  data() {
    return {
      theTime: null,
      theDate: null,
    };
  },
  created:function(){
     this.theTime = new Date(this.settings.deadline),
     this.theDate = new Date(this.settings.deadline)
  },
  methods: {
    submit: function(){
      this.$http.post('/option/deadline',this.payload)
      .then(response => {
        window.location.href = '/logout'
      })
      .catch(x => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
  computed: {
    payload: function(){
      return {
        value: this.theDate.toISOString().split('T')[0] + ' ' + this.theTime.toTimeString().split(' ')[0]
      }
    }

  }
};
</script>

<style>

</style>
