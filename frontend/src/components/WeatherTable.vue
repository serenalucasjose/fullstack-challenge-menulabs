<template>
  <!-- Data -->
  <v-table
    class="weather-table"
    fixed-header
    height="70vh"
  >
    <thead>
      <tr>
        <th class="text-left">
          ID
        </th>
        <th class="text-left">
          Name
        </th>
        <th class="text-left">
          Weather
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- Loading state -->
      <tr v-if="!usersAsArr.length">
        <td colspan="3">
          <p class="text-center text-subtitle ma-8">
            Fetching data from OpenWeather
          </p>
          <v-progress-linear
            indeterminate
            color="secondary"
          ></v-progress-linear>
        </td>
      </tr>
      <!-- Data available -->
      <tr
        v-else
        v-for="({
          id,
          user: {
            name
          },
          weather
        }, idx) in usersAsArr"
        :key="id"
        :id="`weather-row-${idx}`"
        class="weather-row"
      >
        <td>{{ id }}</td>
        <td>{{ name }}</td>
        <weather-table-data-cell
          :weather="weather"
          @onExpandData="onExpandDataHandler(weather)"
        />
      </tr>
    </tbody>
  </v-table>
  <!-- Timestamp -->
  <p class="text-caption text-right text-decoration-underline text-white pt-6">
    {{ lastUpdated }}
  </p>
</template>

<script>
import WeatherTableDataCell from '@/components/WeatherTableDataCell.vue';

import { storeToRefs } from 'pinia'
import { useWeatherStore } from '@/stores/weather'

export default {
  components: { WeatherTableDataCell },
  setup() {
    // Destructure store related state and actions
    const weatherStore = useWeatherStore()
    const { weatherModalOpen } = storeToRefs(weatherStore)

    const {
      toggleModal,
      setWeatherModalData
    } = weatherStore

    return {
      weatherModalOpen,
      toggleModal,
      setWeatherModalData
    }
  },
  data: () => ({
    lastUpdated: null,
    users: {}
  }),
  mounted() {
    // Init data (if possible)
    const cachedData = localStorage.getItem("users");
    this.users = (cachedData) ? JSON.parse(cachedData) : {};
    // Listen for Weather updates
    window.Echo.channel('weather-updates')
      .listen('WeatherUpdate', e => {
        const {
          user,
          id,
          weather
        } = e;

        this.users[id] = {
          user,
          id,
          weather
        };

        this.updateTimestamps();
      })
    // Attach reload handler
    window.onbeforeunload = () => {
      localStorage.setItem("users", JSON.stringify(this.users))
    }
  },
  computed: {
    usersAsArr: function() {
      return Object.values(this.users)
    }
  },
  methods: {
    updateTimestamps() {
      const date = new Date();
      const hours = date.getHours().toString().padStart(2, '0');
      const minutes = date.getMinutes().toString().padStart(2, '0');
      const seconds = date.getSeconds().toString().padStart(2, '0');

      this.lastUpdated = `Last updated at ${hours}:${minutes}:${seconds}`;
    },
    onExpandDataHandler(_weatherModalData) {
      this.toggleModal(true);

      this.setWeatherModalData(_weatherModalData);
    }
  }
}
</script>