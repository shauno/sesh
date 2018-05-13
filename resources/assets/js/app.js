
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const DashboardComponent = Vue.component('app-component', require('./components/DashboardComponent.vue'));
const CreateSurfComponent = Vue.component('create-surf-component', require('./components/CreateSurfComponent'));
const CreateSpotComponent = Vue.component('create-spot-component', require('./components/CreateSpotComponent'));

const routes = [
    { name: 'home', path: '/home', component: DashboardComponent, props: true },
    { name: 'create-surf', path: '/surfs/create', component: CreateSurfComponent, props: true },
    { name: 'create-spot', path: '/spots/create', component: CreateSpotComponent}
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router
});
