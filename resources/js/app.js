
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import Vue from 'vue'
import VueRouter from 'vue-router'
import VuePageTransition from 'vue-page-transition'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

Vue.use(Buefy)

Vue.use(VueRouter)
Vue.use(VuePageTransition)

import routes from './routes'
var router = new VueRouter({
    routes
})


Vue.component('admin-users', require('./components/Admin/Users.vue').default);
Vue.component('admin-texts', require('./components/Admin/Texts.vue').default);
Vue.component('admin-settings', require('./components/Admin/Settings.vue').default);

Vue.component('main-home', require('./components/Main/Home.vue').default);

Vue.component('support-contact', require('./components/Support/Contact.vue').default);

Vue.component('texts-tagging', require('./components/Texts/Tagging.vue').default);
Vue.component('texts-tagged', require('./components/Texts/Tagged.vue').default);
Vue.component('texts-statistics', require('./components/Texts/Statistics.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app'
});
