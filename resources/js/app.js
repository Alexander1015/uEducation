require('./bootstrap');

import Vue from 'vue';
window.Vue = Vue;

import Vuetify from '../plugins/vuetify'
import VueAxios from 'vue-axios';
import axios from 'axios';
import VueRouter from 'vue-router';

import App from './components/App.vue'
import '../sass/app.scss'
import { routes } from './routes';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router: router,
    render: h => h(App),
});
