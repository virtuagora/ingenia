<template>
<div>
  <team-avatars :id="project.group.id" :team-url="teamUrl" :get-group-members="getGroupMembers"></team-avatars>
  <coordinators 
   :granted-budget="grantedBudget == '' ? null : grantedBudget"
   :selected=" selected == '' ? null : selected" 
   :quota="quota == '' ? null : quota" 
   :is-admin="isAdmin ? true : false" 
   :is-coordinator="isCoordinator ? true : null" 
   :coordinators="coordinators ? coordinators : []"
   :id="project.id"
   :update-review="updateReview"
   :assign-coordinator="assignCoordinator"
   :delete-coordinator="deleteCoordinator"
   v-if="isAdmin || isCoordinator"></coordinators>
  <router-view :put-project-note="putProjectNote" :deadline-documents="deadlineDocuments" :selected=" selected == '' ? null : selected" :is-admin="isAdmin ? true : false" :is-coordinator="isCoordinator ? true : null" :coordinators="coordinators ? coordinators : []" :notes="notes ? notes : null" :project="project" :send-request-join="sendRequestJoin" :historias-count="historiasCount"></router-view>
      <hr class="is-marginless">
  <section class="section" id="comentarios">
      <div class="container">
        <div class="columns">
          <div class="column is-8 is-offset-2"> 
            <comments :comments-url="commentsUrl" :comment-url="commentUrl" :reply-url="replyUrl" :like-url="likeUrl"></comments>
          </div>
        </div>
      </div>
    </section>
</div>
</template>

<script>
import TeamAvatars from './TeamAvatars'
import Coordinators from './Coordinators'
import Comments from './Comments'
export default {
  props:['project','deadlineDocuments','isAdmin','notes','grantedBudget','selected','quota','isCoordinator',
  'putProjectNote','coordinators', 'updateReview', 'assignCoordinator','deleteCoordinator',
  "getGroupMembers", 'sendRequestJoin', 'teamUrl', 'commentsUrl', 'commentUrl','replyUrl','likeUrl','historiasCount'],
  components:{
    TeamAvatars,
    Coordinators,
    Comments
  },
  methods: {
    
  }
}
</script>

<style>

</style>
