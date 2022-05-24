require('./bootstrap');

window.Vue = require('vue').default;

import Vuetify from '../plugins/vuetify'

import '../sass/app.scss'

Vue.component('index-public', require('./components/public/Index.vue').default);
Vue.component('index-auth', require('./components/auth/Index.vue').default);
Vue.component('index-admin', require('./components/admin/Index.vue').default);

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
});
