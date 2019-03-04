<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Evaluar</p>
    </header>
    <section class="modal-card-body">
      <div class="notification">
        <i class="fas fa-exclamation-triangle"></i>&nbsp;Edite el campo que necesite, no es necesario que complete todos los campos.
      </div>
     <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label is-size-5" :class="{'has-text-danger': errors.has('names')}">
            <i class="fas fa-angle-double-right"></i> Nombres *
          </label>
          <div class="control">
            <input
              v-model="names"
              data-vv-name="names"
              data-vv-as="'Nombres'"
              type="text"
              v-validate="'required|alpha_spaces'"
              class="input is-medium"
              placeholder="(Requerido)"
            >
            <span v-show="errors.has('names')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>
              &nbsp;{{errors.first('names')}}
            </span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label
            class="label is-size-5"
            :class="{'has-text-danger': errors.has('surnames')}"
          >
            <i class="fas fa-angle-double-right"></i> Apellidos *
          </label>
          <div class="control">
            <input
              v-model="surnames"
              data-vv-name="surnames"
              data-vv-as="'Apellidos'"
              type="text"
              v-validate="'required|alpha_spaces'"
              class="input is-medium"
              placeholder="(Requerido)"
            >
            <span v-show="errors.has('surnames')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>
              &nbsp;{{errors.first('surnames')}}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="message is-primary">
      <div class="message-body">
        <i class="fas fa-exclamation-triangle"></i> Es importante que todos los participantes tengan sus nombres y apellidos tal como figura en el Documento de Identidad (DNI).
      </div>
    </div>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-link" type="button" @click="submit()">Guardar</button>
      <button class="button is-dark" type="button" @click="$parent.close()">Cerrar</button>
    </footer>
  </div>
</template>

<script>

export default {
  props: ["user"],
  data() {
    return {
      names: this.user.names,
      surnames: this.user.surnames
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
          this.$http.post(`/user/${this.user.id}/update-names`, this.payload)
          .then(response => {
            this.$emit('update')
            this.$parent.close()
            this.$snackbar.open({
              message: "Nombres y apellidos guardados correctamente",
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
        names: this.names,
        surnames: this.surnames
      }
    }
  }
};
</script>