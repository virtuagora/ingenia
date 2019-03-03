<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-10 is-offset-1">
            <stories :isAdmin="isAdmin" :isCoordinator="isCoordinator" :project-id="project.id" :latest="false"></stories>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Localidad from "../utils/GetLocalidad";
import Stories from "./Stories";

export default {
  props: [
    "project",
    "isAdmin",
    "isCoordinator",
    "selected"
  ],
  components: {
    Stories
  }
};
</script>

<style>
</style>
