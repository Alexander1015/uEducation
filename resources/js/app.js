require('./bootstrap');

import Vue from 'vue';
window.Vue = Vue;

import Vuetify from '../plugins/vuetify'
import VueAxios from 'vue-axios';
import axios from 'axios';
import VueRouter from 'vue-router';
import VueSweetalert2 from "vue-sweetalert2"
import 'sweetalert2/dist/sweetalert2.min.css';
import CKEditor from '@ckeditor/ckeditor5-vue2';

const options = {
    confirmButtonColor: '#009664',
    cancelButtonColor: '#CD1414'
};

import App from './components/App.vue'
import '../sass/app.scss'
import {routes} from './routes';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VueSweetalert2, options);
Vue.use(CKEditor);

const router = new VueRouter({mode: 'history', routes: routes});

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router: router,
    render: h => h(App)
});
