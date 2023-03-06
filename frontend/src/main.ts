// Store
import { createApp } from "vue";
import { createPinia } from "pinia";
// App
import App from "./App.vue";
import router from "./router";
// Websocket related
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
// Vuetify (UI Library)
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css';


window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'local',
  wsHost: '127.0.0.1',
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
  cluster: 'mt1'
});

import "./assets/main.css";

const vuetify = createVuetify({
  components,
  directives
})

const app = createApp(App);

app.use(createPinia());
app.use(vuetify);
app.use(router);

app.mount("#app");
