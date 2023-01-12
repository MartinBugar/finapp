
import {createApp} from 'vue';


require('./bootstrap');
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue';


import App from './App.vue';
import axios from 'axios';
import router from './router';

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(BootstrapIconsPlugin);
app.use(router);
app.mount('#app');
