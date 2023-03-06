import { defineStore } from "pinia";

export const useWeatherStore = defineStore("weatherStore", {
  state: () => ({
    weatherModalData: {},
    weatherModalOpen: false
  }),
  actions: {
    toggleModal(_bool: boolean) {
      this.weatherModalOpen = _bool
    },
    setWeatherModalData(_weather: object) {
      this.weatherModalData = _weather
    }
  }
});
