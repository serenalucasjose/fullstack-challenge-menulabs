<script setup lang="ts">
import WeatherTable from "@/components/WeatherTable.vue";
import WeatherModal from "@/components/WeatherModal.vue";
</script>

<template>
  <main class="home-page">
    <v-container
      class="pa-4 mb-8 text-blue-grey-darken-2 bg-grey-lighten-5"
      fluid>
      <h1 class="text-h4 mb-2">Weather service</h1>     
      <p class="text-caption pl-1 font-weight-bold">Select any user to get detailed information</p>     
    </v-container>
    <v-container fluid>
      <weather-table></weather-table>
      <weather-modal></weather-modal>
    </v-container>
  </main>
</template>
