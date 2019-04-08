<template>
  <div>
    <div class="tabs">
      <ul>
        <li :class="{'is-active': $route.name == 'userVerEquipo'}">
          <router-link :to="{ name: 'userVerEquipo'}">Ver equipo</router-link>
        </li>
        <li
          :class="{'is-active': $route.name == 'userEditarEquipo'}"
          v-if=" allowResponsables && !isFormClosed(deadline)"
        >
          <router-link :to="{ name: 'userEditarEquipo'}">Editar datos</router-link>
        </li>
        <li :class="{'is-active': $route.name == 'userVerIntegrantes'}" v-if="allowResponsables">
          <router-link :to="{ name: 'userVerIntegrantes'}">Ver los integrantes</router-link>
        </li>
      </ul>
    </div>
    <h1 class="subtitle is-3">Integrantes del equipo</h1>
    <p>Para asignar al co-responsable, hace clic sobre el boton con el escudo
      <i class="fas fa-shield-alt"></i>. No te preocupes. Podes cambiarlo cuando quieras.
    </p>
    <br>
    <b-message
      class="has-text-centered"
      type="is-warning"
      v-if="isFormClosed(deadlineDocuments)"
    >El tiempo limite para completar la documentación ha cerrado. Si tenes algún requerimiento que quedo pendiente, contactate con Gabinete Joven para que te asesoren.</b-message>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Nombre y apellido</th>
          <th v-if="!isFormClosed(deadlineDocuments)" class="has-text-centered">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(member,index) in members" :key="index">
          <td>
            <Avatar :user="member" class="inline-image" size="32"/>
            &nbsp;&nbsp;{{member.names}} {{member.surnames}}
            <span
              class="tag is-warning is-pulled-right"
              v-if="member.pivot.relation === 'responsable'"
            >
              <i class="fas fa-star"></i>&nbsp;Responsable
            </span>
            <div
              class="tags has-addons is-pulled-right"
              v-if="member.pivot.relation === 'co-responsable'"
            >
              <span class="tag is-link">
                <i class="fas fa-shield-alt"></i>&nbsp;Co-responsable
              </span>
              <b-tooltip label="Quitar co-responsable" type="is-dark">
                <a
                  @click="removeSecondInCharge(member.id)"
                  v-if="user.groups[0].pivot.relation === 'responsable' && !isFormClosed(deadlineDocuments)"
                  class="tag is-delete"
                ></a>
              </b-tooltip>
            </div>
          </td>
          <td v-if="!isFormClosed(deadlineDocuments)">
            <p class="is-size-7"
                  v-if="!group.second_in_charge && member.pivot.relation !== 'responsable' && user.groups[0].pivot.relation === 'responsable'"
                >
                  <a @click="assignSecondInCharge(member.id)">
                    <i class="fas fa-plus fa-fw"></i>
                    <i class="fas fa-shield-alt"></i>&nbsp;Asignar co-responsable
                  </a>
                </p>
                <p class="is-size-7"
                  v-if="member.pivot.relation !== 'responsable' && member.pivot.relation !== 'co-responsable' && user.groups[0].pivot.relation === 'responsable'"
                >
                  <a @click="openTransferResponsable(member.id, member.names + ' ' + member.surnames)">
                    <i class="fas fa-arrow-right fa-fw"></i>
                    <i class="fas fa-star fa-fw"></i>&nbsp;Transferir rol responsable
                  </a>
                </p>
                <p class="is-size-7"
                  v-if="member.pivot.relation !== 'responsable' && member.pivot.relation !== 'co-responsable' && user.groups[0].pivot.relation === 'responsable'"
                >
                  <a @click="openRemoveUser(member.id, member.names + ' ' + member.surnames)" class="has-text-danger">
                    <i class="far fa-trash-alt"></i>&nbsp;Quitar del equipo
                  </a>
                </p>
          </td>
        </tr>
      </tbody>
    </table>
    <hr>
    <h1 class="title is-5">Invitaciones</h1>
    <table class="table is-bordered is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Email</th>
          <th class="has-text-centered" width="150px">Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if=" group.invitations.length === 0">
          <td colspan="2">No hay invitaciones para listar</td>
        </tr>
        <tr v-for="(invitation, index) in invitaciones" :key="index">
          <td>
            <get-usuario :id="invitation.user_id" v-if="invitation.user_id"/>
            <span v-else>{{invitation.email}}</span>
            <br>
            <p class="is-size-7 has-text-primary" v-if="invitation.user_id == null">
              <i class="fas fa-exclamation-circle"></i><i>&nbsp;(Aun no registrado en la plataforma)</i>
            </p>
            <p class="is-size-7 nl2br">
              <i>{{invitation.comment}}</i>
            </p>
          </td>
          <td class="has-text-centered">
            <span v-if="invitation.state == 'accepted'">
              <i class="fas fa-check fa-fw"></i>&nbsp;Aceptada
            </span>
            <span v-if="invitation.state == 'pending'">
              <i class="fas fa-clock fa-fw"></i>&nbsp;Pendiente
            </span>
          </td>
        </tr>
      </tbody>
    </table>
    <h1 class="title is-5">Solicitudes para entrar</h1>
    <table class="table is-bordered is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Email</th>
          <th class="has-text-centered" width="150px">Estado</th>
          <th v-if="!isFormClosed(deadlineDocuments)" class="has-text-centered" width="60px">Acciones</th>
        </tr>
      </thead>
      <tbody v-if="cantSolicitudes > 0">
        <tr v-for="(invitation, index) in solicitudes" :key="index">
          <td>
            <get-usuario :id="invitation.user_id"/>
            <p class="is-size-7 nl2br">
              <i>{{invitation.comment}}</i>
            </p>
          </td>
          <td class="has-text-centered">
            <i class="fas fa-clock fa-fw"></i>&nbsp;Solicitado
          </td>
          <td v-if="!isFormClosed(deadlineDocuments)" class="has-text-centered">
            <button
              @click="openAcceptRequest(invitation.id, invitation.comment)"
              class="button is-outlined is-small is-success"
            >
              <i class="fas fa-check"></i>
            </button>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td colspan="3">No hay solicitudes para listar</td>
        </tr>
      </tbody>
    </table>
    <b-modal ref="modalRemoveUser" :active.sync="showRemoveModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar a {{deleteUserName}} del equipo?</h1>
            <p>Si desea volver a agregarlo al equipo, debera volver a enviar la invitación</p>
            <br>
            <div class="field">
              <div class="control">
                <button @click="submitRemoveUser" class="button is-white">Si, quitar del equipo</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalRemoveUser.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-modal ref="modalTransfer" :active.sync="showTransferModal" :width="640" scroll="keep">
      <div class="notification is-warning">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-exclamation-triangle fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">Estas por asignar a {{transferUserName}} como el/la RESPONSABLE del equipo</h1>
            <p>Se te quitarán los privilegios de responsable. El nuevo o la nueva responsable deberá coordinar el equipo y las invitaciones. En caso de que lo estés haciendo porque queres salir del equipo, deberás pedirle a el nuevo o la nueva responsable del equipo que te quite del equipo.</p>
            <br>
            <div class="field">
              <div class="control">
                <button @click="submitTransfer" class="button is-white">Transferir rol de responsable</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalRemoveUser.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-modal
      ref="modalAcceptRequest"
      :active.sync="showAcceptRequestModal"
      :width="640"
      scroll="keep"
    >
      <div class="box">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-question fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea aceptar la solicitud?</h1>
            <p>Mensaje recibido:</p>
            <p class="is-size-7 nl2br">
              <i>{{acceptRequestComment}}</i>
            </p>
            <br>
            <div class="field">
              <div class="control">
                <button @click="submitAcceptRequest" class="button is-success">Si, agregar al equipo</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalAcceptRequest.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-modal
      ref="modalRemoveInvitation"
      :active.sync="showRemoveInvitationModal"
      :width="640"
      scroll="keep"
    >
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar la invitación a {{deleteInvitationEmail}}?</h1>
            <div class="field">
              <div class="control">
                <button
                  @click="submitRemoveUser"
                  class="button is-white is-outlined"
                >Si, eliminar invitación</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalRemoveInvitation.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
import GetUsuario from "../../utils/GetUsuario";
import Avatar from "../../utils/Avatar";

export default {
  props: [
    "teamUrl",
    "deadline",
    "deadlineDocuments",
    "getGroupMembers",
    "assignGroupSecond",
    "deleteGroupSecond",
    "acceptGroupRequest",
    "removeGroupUser",
    "removeGroupInvitation"
  ],
  components: {
    GetUsuario,
    Avatar
  },
  data() {
    return {
      isLoading: true,
      response: {
        ok: true
      },
      showRemoveModal: false,
      deleteUser: null,
      deleteUserName: null,
      transferUser: null,
      transferUserName: null,
      showRemoveInvitationModal: false,
      deleteInvitation: null,
      deleteInvitationEmail: null,
      showAcceptRequestModal: false,
      showTransferModal: false,
      acceptRequestId: null,
      acceptRequestComment: null,
      user: {},
      group: {
        invitations: []
      },
      members: []
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    this.getEquipo(this.user.groups[0].id);
  },
  methods: {
    getEquipo: function(id) {
      Promise.all([
        this.$http.get(this.fetchTeamUrl),
        this.$http.get(this.fetchGroupMembers)
      ])
        .then(responses => {
          this.group = responses[0].data;
          this.members = responses[1].data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    openRemoveUser: function(id, name) {
      this.deleteUser = id;
      this.deleteUserName = name;
      this.showRemoveModal = true;
    },
    openTransferResponsable: function(id, name) {
      this.transferUser = id;
      this.transferUserName = name;
      this.showTransferModal = true;
    },
    openRemoveInvitation: function(id, email) {
      this.deleteInvitation = id;
      this.deleteInvitationEmail = email;
      this.showRemoveInvitationModal = true;
    },
    openAcceptRequest: function(id, comment) {
      this.acceptRequestId = id;
      this.acceptRequestComment = comment;
      this.showAcceptRequestModal = true;
    },
    submitRemoveUser: function() {
      this.showRemoveModal = false;
      console.log("Sending form!");
      this.isLoading = true;
      let formUrl = this.removeGroupUser.replace(
        ":gro",
        this.user.groups[0].id
      );
      formUrl = formUrl.replace(":usr", this.deleteUser);
      this.$http
        .delete(formUrl)
        .then(response => {
          this.$snackbar.open({
            message:
              this.deleteUserName + " se eliminó del equipo correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.forceUpdateState("userPanel")
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    submitRemoveInvitation: function() {
      this.showRemoveModal = false;
      console.log("Sending form!");
      this.isLoading = true;
      this.$http
        .post(this.saveTeamUrl, this.deleteUser)
        .then(response => {
          this.$snackbar.open({
            message: "Los datos del equipo han sido actualizados",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
          this.response.ok = true;
          this.forceUpdateState("userPanel")
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    submitAcceptRequest: function() {
      this.showAcceptRequestModal = false;
      console.log("Sending form!");
      let formUrl = this.acceptGroupRequest.replace(
        ":inv",
        this.acceptRequestId
      );
      this.isLoading = true;
      this.$http
        .post(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "Solicitud aceptada ¡Nuevo miembro del equipo! 🎉",
            type: "is-success",
            actionText: "OK"
          });
          this.forceUpdateState("userPanel")
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
     submitTransfer: function() {
      this.isLoading = true;
      this.$http
        .put(`/group/${this.user.groups[0].id}/leader/${this.transferUser}`)
        .then(response => {
          this.$snackbar.open({
            message: "El co-responsable ha sido asignado correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.forceUpdateState("userPanel").then(user => {
            this.isLoading = false;
            this.user = user;
            this.$router.push({ name: "panelOverview" });
          });
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    assignSecondInCharge: function(id) {
      console.log("Sending form!");
      let formUrl = this.assignGroupSecond.replace(
        ":gro",
        this.user.groups[0].id
      );
      formUrl = formUrl.replace(":usr", id);
      this.isLoading = true;
      this.$http
        .put(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "El co-responsable ha sido asignado correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.response.ok = true;
          this.forceUpdateState("userPanel")
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    removeSecondInCharge: function(id) {
      console.log("Sending form!");
      let formUrl = this.deleteGroupSecond.replace(
        ":gro",
        this.user.groups[0].id
      );
      formUrl = formUrl.replace(":usr", id);
      this.isLoading = true;
      this.$http
        .delete(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "El co-responsable ha sido desvinculado correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.response.ok = true;
          this.forceUpdateState("userPanel")
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
  },
  computed: {
    fetchTeamUrl: function() {
      return this.teamUrl.replace(":gro", this.user.groups[0].id);
    },
    fetchGroupMembers: function() {
      return this.getGroupMembers.replace(":gro", this.user.groups[0].id);
    },
    cantInvitaciones: function() {
      return this.group.invitations.filter(x => {
        return x.state == "pending";
      }).length;
    },
    cantSolicitudes: function() {
      return this.group.invitations.filter(x => {
        return x.state == "requested";
      }).length;
    },
    onlyResponsable: function() {
      return this.$store.getters.onlyResponsable;
    },
    onlyCoresponsable: function() {
      return this.$store.getters.onlyCoresponsable;
    },
    solicitudes: function() {
      return this.group.invitations.filter(x => {
        return x.state == "requested";
      });
    },
    invitaciones: function() {
      return this.group.invitations.filter(x => {
        return x.state == "pending" || x.state == "accepted";
      });
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
