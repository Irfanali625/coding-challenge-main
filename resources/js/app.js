
require('./bootstrap');

import { createApp } from 'vue'
import Welcome from './Welcome'
import axios from "axios";

window.axios.defaults.baseURL = process.env.MIX_API_URL;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


const app = createApp({})
app.component('welcome', Welcome)

app.mount('#app')
