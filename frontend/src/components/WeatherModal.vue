<template>
  <v-dialog
    v-model="weatherModalOpen"
    fullscreen
    :scrim="false"
    transition="dialog-bottom-transition"
  >
    <v-card>
      <v-toolbar
        dark
        color="grey-lighten-5"
      >
        <v-btn
          icon
          dark
          @click="toggleModal(false)"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
        <div class="d-flex align-center pr-4">
          <p class="mr-4 text-light-blue-darken-3">Temperature: {{ temperature }}</p>
          <v-icon
            color="light-blue-darken-3"
            :icon="getWeatherIcon"
            :size="48"
          />
        </div>
      </v-toolbar>
      <!-- Data -->
      <div class="modal-card pa-8">
        <h5 class="text-h5 mb-4">Feels Like: {{ feelsLike }}</h5>
        <p class="text-body mb-1">Pressure: {{ pressure }}</p>
        <p class="text-body  mb-1">Humidity: {{ humidity }}</p>
        <p class="text-body  mb-1">Wind Speed: {{ windSpeed }}</p>
        <p class="text-body  mb-1">Cloudiness: {{ cloudiness }}</p>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
import { storeToRefs } from 'pinia'
import { useWeatherStore } from '@/stores/weather'

export default {
  setup() {
    // Destructure store related state and actions
    const weatherStore = useWeatherStore()
    const { weatherModalOpen, weatherModalData } = storeToRefs(weatherStore)

    const { toggleModal } = weatherStore

    return {
      weatherModalOpen,
      toggleModal,
      weatherModalData
    }
  },
  computed: {
    temperature() {
      const {
        main: {
          temp
        }
      } = this.weatherModalData
      
      return temp
    },
    feelsLike() {
      const {
        main: {
          feels_like
        }
      } = this.weatherModalData

      return feels_like
    },
    pressure() {
      const {
        main: {
          pressure
        }
      } = this.weatherModalData

      return pressure
    },
    humidity() {
      const {
        main: {
          humidity
        }
      } = this.weatherModalData
      
      return humidity
    },
    windSpeed() {
      const {
        wind: {
          speed
        }
      } = this.weatherModalData
      
      return speed
    },
    cloudiness() {
      const {
        clouds: {
          all
        }
      } = this.weatherModalData
      
      return all
    },
    getWeatherIcon() {
      if (this.temperature > 30) {
        return 'mdi-sun-thermometer';
      } else if (this.temperature > 20) {
        return 'mdi-white-balance-sunny';
      } else {
        return 'mdi-snowflake-alert';
      }
    },
  },
}
</script>