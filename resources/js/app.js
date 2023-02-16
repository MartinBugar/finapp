
import {createApp} from 'vue';


require('./bootstrap');
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue';
import BootstrapVue3 from 'bootstrap-vue-3'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css'

import App from './App.vue';
import axios from 'axios';
import router from './router';

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(BootstrapIconsPlugin);
app.use(BootstrapVue3);
app.use(router);
app.mount('#app');
