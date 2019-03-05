<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Nuevo email de {{user.names}} {{user.surnames}}</p>
    </header>
    <section class="modal-card-body">
      <div>
            <div class="field">
            <label class="label">Escriba el nuevo email del usuario</label>
              <div class="control">
                <input
                  class="input"
                  v-model="email"
                  data-vv-name="email"
                  data-vv-as="'Email'"
                  v-validate="'required|email'"
                  type="email"
                  placeholder="Nuevo email"
                >
                <span v-if="errors.has('email')" class="help is-danger">
                  <i class="fas fa-times-circle fa-fw"></i>
                  &nbsp;{{errors.first('email')}}
                </span>
              </div>
        </div>
      </div>
    <b-loading :active.sync="isLoading"></b-loading>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-link" type="button" @click="submit()">Guardar</button>
      <button class="button is-dark" type="button" @click="$parent.close()">Close</button>
    </footer>
  </div>
</template>

<script>

export default {
  props: ["user"],
  data() {
    return {
      email: '',
      isLoading: false,
    };
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error en el formulario. Verifíquelo",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$http
            .post(`/user/${this.user.id}/pending-email`, {
              email: this.email
            })
            .then(response => {
              this.$emit('update')
              this.$parent.close();
              this.$snackbar.open({
                message: "Nuevo email asignado. El usuario debe re revalidar su nuevo email para que pueda comenzar a utilizarlo",
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
        })
        .catch(error => {
          console.error(error)
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
};
</script>