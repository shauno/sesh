
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

Vue.mixin({
    data: function() {
        return {
            'windSpeeds': [
                {
                    value: '',
                    name: 'Wind Speed',
                    selectable: true,
                },
                {
                    value: 0,
                    name: 'Calm',
                    selectable: true,
                },
                {
                    value: 1,
                    name: 'Calm to Light',
                    selectable: false,
                },
                {
                    value: 2,
                    name: 'Light',
                    selectable: true,
                },
                {
                    value: 3,
                    name: 'Light to Moderate',
                    selectable: false,
                },
                {
                    value: 4,
                    name: 'Moderate',
                    selectable: true,
                },
                {
                    value: 5,
                    name: 'Moderate to Strong',
                    selectable: false,
                },
                {
                    value: 6,
                    name: 'Strong',
                    selectable: true,
                },
                {
                    value: 7,
                    name: 'Strong to Gale',
                    selectable: false,
                },
                {
                    value: 8,
                    name: 'Gale',
                    selectable: true,
                },
            ],
            'windDirections': [
                'Wind Direction',
                'Offshore',
                'Cross-Offshore',
                'Cross-Shore',
                'Cross-Onshore',
                'Onshore',
            ],
        };
    },
    methods: {
        windSpeed(speed) {
            return this.windSpeeds[speed+1].name;
        },
        selectableWindSpeeds() {
            return this.windSpeeds.filter(function (item) {
                return item.selectable;
            })
        },
        windDirection(direction) {
            return this.windDirections[direction];
        }
    }
});

const app = new Vue({
    el: '#app',
    router
});
