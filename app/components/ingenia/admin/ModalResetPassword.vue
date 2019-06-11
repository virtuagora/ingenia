<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Reset de password para {{user.names}} {{user.surnames}}</p>
    </header>
    <section class="modal-card-body">
      <!-- <p>Esta por mandar un email al usuario para que pueda recuperar su contraseña. ¿Esta seguro?</p>
      -->
        <div class="field">
          <label class="label">Nueva contraseña *</label>
          <div class="control">
            <input
              type="password"
              v-model="password"
              name="password"
              v-validate="'required|min:6'"
              class="input "
              ref="password"
              style=""
              placeholder="Ingresá la nueva contraseña"
            >
            <span class="help is-danger" v-show="errors.has('password')">
              <i class="fas fa-times-circle fa-fw"></i> Error. La contraseña no puede ser vacia, (Mínimo 6 caracteres)
            </span>
          </div>
        </div>
        <div class="field">
          <label class="label">Repeta la nueva contraseña *</label>
          <div class="control">
            <input
              type="password"
              v-model="repeat"
              name="repeat"
              v-validate="'required|confirmed:password'"
              class="input "
              style=""
              placeholder="Reescribi la nueva contraseña"
            >
            <span class="help is-danger" v-show="errors.has('repeat')">
              <i class="fas fa-times-circle fa-fw"></i> Error. Las contraseñas no coinciden
            </span>
          </div>
        </div>
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
      isLoading: false,
      password: null,
      repeat: null,
    };
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error. Verifique los campos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$http
            .post(`/user/${this.user.id}/password`, {
              new: this.password,
              repeat: this.repeat
            })
            .then(response => {
              this.$snackbar.open({
                message: "Se cambio la contraseña correctamente",
                type: "is-success",
                actionText: "OK"
              });
              this.isLoading = false;
              this.$parent.close();
              this.$emit("update");
            })
            .catch(error => {
              console.error(error.message);
              this.isLoading = false;
              this.$snackbar.open({
                message: "Ocurrio un error: " + error.message,
                type: "is-danger",
                actionText: "Cerrar"
              });
              return false;
            });
        })
        .catch(error => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
      // this.isLoading = true;
      // this.$http
      //   .post(`/reset-password`, {
      //     email: this.user.email
      //   })
      //   .then(response => {
      //     this.$emit("update");
      //     this.$parent.close();
      //     this.$snackbar.open({
      //       message: "Enviado el email de recuperacion de password",
      //       type: "is-success",
      //       actionText: "Ok!"
      //     });
      //   })
      //   .catch(error => {
      //     console.error(error.message);
      //     this.$snackbar.open({
      //       message: "Error inesperado",
      //       type: "is-danger",
      //       actionText: "Cerrar"
      //     });
      //   });
    }
  }
};
</script>