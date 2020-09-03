/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./jquery.magnific-popup.min');


window.Vue = require('vue');

import Auth from './Helper/Auth.js'

Vue.prototype.$auth = new Auth(window.auth)



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app-dashboard', require('./components/dashboard/Dashboard.vue').default);
Vue.component('app-vote', require('./components/Vote.vue').default);
Vue.component('app-comments', require('./components/comment/Comments.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import router from './router/router.js'
import store from './store/store.js'
import vuetify from './vuetify/index.js'
import VueProgressBar from 'vue-progressbar'

import SlidingPagination from 'vue-sliding-pagination'

Vue.use(VueProgressBar, {
  color: '#007bff',
  failedColor: 'red',
  height: '3px'
})

Vue.component('SlidingPagination',SlidingPagination);

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify,
});



$('.popup-youtube').magnificPopup({type:'iframe'});
	//iframe scripts
	$.extend(true, $.magnificPopup.defaults, {
		iframe: {
			patterns: {
				//youtube videos
				youtube: {
					index: 'youtube.com/',
					id: 'v=',
					src: 'https://www.youtube.com/embed/%id%?autoplay=1'
				}
			}
		}
	});
