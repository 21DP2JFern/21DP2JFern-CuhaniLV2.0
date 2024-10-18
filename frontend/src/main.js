import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import header from './components/Header.vue'

import axios from 'axios';

axios.defaults.baseURL = 'http://10.0.20.56:8081';

const app = createApp(App)

app.use(router)

app.mount('#app')
