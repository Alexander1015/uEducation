require('./bootstrap');

window.Vue = require('vue').default;

import Vuetify from '../plugins/vuetify'

import '../sass/app.scss'

Vue.component('index-public', require('./components/Index.vue').default);

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
});
