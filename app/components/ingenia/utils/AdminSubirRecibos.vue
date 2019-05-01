<template>
  <div class="box">
    <h1 class="subtitle is-4">
      <i class="fa fa-plus"></i> Subir nuevo recibo
    </h1>
    <form :action="formUrl" ref="formReceipt" method="post" enctype="multipart/form-data">
      <hr>
      <div class="field">
        <label class="label">Fecha del comprobante</label>
        <b-datepicker
          placeholder="Hace clic para seleccionar la fecha"
          v-model="inputReciboFecha"
          :mobile-native="false"
          :date-formatter="(date) => date.toLocaleDateString('es-AR')"
          icon="calendar-alt"
        ></b-datepicker>
        <span v-show="errors.has('inputReciboFecha')" class="help is-danger">
          <i class="fas fa-times-circle fa-fw"></i>
          &nbsp;{{errors.first('inputReciboFecha')}}
        </span>
        <input
          type="hidden"
          v-model="fechaFinal"
          v-validate="'required'"
          data-vv-scope="recibos"
          name="fecha"
        >
      </div>
      <div class="field">
        <label class="label">Detalle</label>
        <div class="control">
          <input
            class="input"
            name="detalle"
            type="text"
            v-validate="'required'"
            data-vv-scope="recibos"
            placeholder="Escribi el detalle de la compra (Ej: Productos y/o lugar donde lo compraste"
          >
          <div class="help">Si son varios productos, listalos en el campo.</div>
          <span v-show="errors.has('detalle')" class="help is-danger">
            <i class="fas fa-times-circle fa-fw"></i>
            &nbsp;{{errors.first('detalle')}}
          </span>
        </div>
      </div>
      <div class="field">
        <label class="label">Monto</label>
        <div class="control">
          <input
            class="input"
            name="monto"
            v-validate="'required|numeric'"
            data-vv-scope="recibos"
            type="text"
            placeholder="Monto en AR$"
          >
          <span v-if="errors.has('monto')" class="help is-danger">
            <i class="fas fa-times-circle fa-fw"></i>
            &nbsp;{{errors.first('monto')}}
          </span>
          <span v-else class="help">Ingrese números sin decimal, puntos o comas</span>
        </div>
      </div>
      <b>Foto/Archivo del comprobante</b>
      <p>Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
      <br>
      <b-field class="file is-medium">
        <b-upload v-model="file" :required="true" name="archivo">
          <a class="button is-link is-medium">
            <b-icon icon="upload"></b-icon>
            <span>Click para cargar</span>
          </a>
        </b-upload>
        <span class="file-name" style="max-width: none;" v-if="file">{{ file.name }}</span>
      </b-field>
      <p
        v-show="!isFileOk && file !== null"
        class="has-text-danger"
      >Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
      <hr>
      <div class="field">
        <div class="control is-clearfix">
          <a
            @click="submit"
            type="submit"
            class="button is-info is-pulled-right"
            :class="{'is-loading': isLoading}"
          >
            <i class="fa fa-paper-plane fa-fw"></i>&nbsp;Enviar
          </a>
        </div>
      </div>
    </form>
    <b-loading :is-full-page="true" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ["formUrl"],
  data() {
    return {
      showConfirmSending: false,
      inputReciboFecha: null,
      fechaFinal: "",
      recibos: [],
      isLoading: false,
      file: null,
      mimes: [
        "application/pdf",
        "invalid/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "image/jpeg",
        "image/pjpeg"
      ]
    };
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll("recibos")
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
          this.$refs.formReceipt.submit();
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
      if (this.file === null) return false;
      if (this.file.size > 3145728) return false;
      if (!this.mimes.includes(this.file.type)) return false;
      return true;
    }
  },
  watch: {
    inputReciboFecha: function(newVal) {
      this.fechaFinal = newVal.toISOString().split("T")[0];
    }
  }
};
</script>
