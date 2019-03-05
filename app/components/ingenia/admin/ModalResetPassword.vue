<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Reset de password para {{user.names}} {{user.surnames}}</p>
    </header>
    <section class="modal-card-body">
      <p>Esta por mandar un email al usuario para que pueda recuperar su contraseña. ¿Esta seguro?</p>
      <b-loading :active.sync="isLoading"></b-loading>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-link" type="button" @click="submit()">Enviar</button>
      <button class="button is-dark" type="button" @click="$parent.close()">Close</button>
    </footer>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      isLoading: false
    };
  },
  methods: {
    submit: function() {
      this.isLoading = true;
      this.$http
        .post(`/reset-password`, {
          email: this.user.email
        })
        .then(response => {
          this.$emit("update");
          this.$parent.close();
          this.$snackbar.open({
            message: "Enviado el email de recuperacion de password",
            type: "is-success",
            actionText: "Ok!"
          });
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  }
};
</script>