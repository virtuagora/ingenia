<template>
  <div>
    <h1 class="subtitle is-3">Subir carta de conformidad</h1>
      <b-message>Uno de los requisitos para que tu proyecto sea admitido en INGENIA es que los integrantes de tu equipo firme la carta de conformidad. Una vez que termines de armar el equipo, todos deben firmar la carta. Una vez listo, subí un archivo donde se vea la carta de conformidad firmada por todos los integrantes.
        <br>Tamaño del archivo: 3MB. Se aceptan .JPG, .JPEG, .PDF, .DOC o .DOCX
      </b-message>
    <div v-if="!isFormClosed(deadlineDocuments)">
      <div class="notification" v-show="verifying">
        <i class="fas fa-cog fa-spin"></i>&nbsp;Revisando si enviaste la carta de conformidad . . .
      </div>
      <div class="notification is-success is-clearfix" v-show="user.groups[0].uploaded_agreement && !verifying">
        <i class="fas fa-check fa-fw"></i>La carta de conformidad ha sido enviada y guardada correctamente
        <a :href="'/group/'+user.groups[0].id+'/agreement'" target="_blank" class="is-pulled-right button is-small is-white">
          <i class="fas fa-download"></i>&nbsp;Descargar</a>
      </div>
      <div v-show="user.groups[0].uploaded_agreement && !verifying">
        <p>
          <i>Al subir de nuevo la carta, se sobreescribe el archivo anterior.</i>
        </p>
        <br>
      </div>
      <form :action="saveAgreementUrl.replace(':gro',this.user.groups[0].id)" ref="formConformidad" method="post" enctype="multipart/form-data">
        <b-field class="file is-medium">
          <b-upload v-model="file" :required="true" name="archivo">
            <a class="button is-link is-medium">
              <b-icon icon="upload"></b-icon>
              <span>Click para cargar</span>
            </a>
          </b-upload>
          <span class="file-name" style="max-width: none;" v-if="file">
            {{ file.name }}
          </span>
        </b-field>
        <p v-show="!isFileOk && file !== null" class="has-text-danger">Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
        <div class="field">
          <div class="control is-clearfix">
            <a @click="submit" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
              <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
          </div>
        </div>
      </form>
    </div>
    <b-message
      class="has-text-centered"
      type="is-warning"
      v-else
    >El tiempo limite para completar la documentación ha cerrado. Si tenes algún requerimiento que quedo pendiente, contactate con Gabinete Joven para que te asesoren.</b-message>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ["saveAgreementUrl", "deadlineDocuments"],
  data() {
    return {
      pendiente: false,
      rechazado: false,
      verificado: false,
      file: null,
      isLoading: false,
      user: {},
      verifying: true,
      mimes: ['application/pdf','invalid/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/pjpeg']

    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    this.forceUpdateState("userPanel")
      .then(user => {
        this.user = this.$store.state.user;
        this.verifying = false;
      })
      .catch(e => {
        this.$snackbar.open({
          message: "Error al verificar la carta de conformidad.",
          type: "is-danger",
          actionText: "Cerrar"
        });
      });
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
          this.$refs.formConformidad.submit();
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
    isFileOk: function() {
      if(this.file === null) return false
      if(this.file.size > 3145728) return false
      if(!this.mimes.includes(this.file.type)) return false
      return true
    }
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (
        vm.user.groups[0] !== undefined &&
        (vm.user.groups[0].pivot.relation === "responsable" ||
        vm.user.groups[0].pivot.relation === "co-responsable")
      ) {
        console.log("Authorized");
      } else {
        console.log("Unauthorized - Kicking to dashboard!");
        next({ name: "panelOverview" });
      }
    });
  }
};
</script>
