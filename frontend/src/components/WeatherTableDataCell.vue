<template>
  <td> 
    <div class="weather-cell pt-1">
      <p class="text-subtitle-1 mb-1">{{ weatherName }}</p>
      <p class="text-subtitle-2 mb-2">{{ weatherDescription }}</p>
      <p class="text-caption text-right">
        {{ weatherTemp }}° -
        <v-btn
          class="px-4"
          color="light-blue-darken-3"
          size="x-small"
          variant="plain"
          @click.stop="$emit('onExpandData')">
          More
        </v-btn>
      </p>
    </div>
  </td>
</template>

<script>
export default {
  props: {
    weather: {
      type: Object,
      default: null
    }
  },
  computed: {
    weatherName() {
      const { weather: [{ main }] } = this.weather;

      return main;
    },
    weatherDescription() {
      if (!this.weather) return "";

      const { weather: [{ description }] } = this.weather;

      return description;
    },
    weatherTemp() {
      if (!this.weather) return "";

      const { main: { temp } } = this.weather;

      return temp;
    }
  }
}
</script>