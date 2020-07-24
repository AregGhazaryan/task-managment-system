/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueFlashMessage from 'vue-flash-message';
import VuePaginate from 'vue-paginate'
import Multiselect from 'vue-multiselect'
import router from './router'
import store from './store'
import App from './App.vue'

require('vue-flash-message/dist/vue-flash-message.min.css');
require('./bootstrap');


// register globally
window.Vue = require('vue');
Vue.config.productionTip = false

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

axios.defaults.withCredentials = true

Vue.use(VueFlashMessage);
Vue.use(VuePaginate)
Vue.use(require('vue-resource'));

Vue.component('multiselect', Multiselect)
Vue.component('pagination', require('laravel-vue-pagination'));

new Vue({
    router,
    store,
    created() {
        const userInfo = localStorage.getItem('user')
        if (userInfo) {
            const userData = JSON.parse(userInfo)
            this.$store.commit('setUserData', userData)
        }
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    this.$store.dispatch('logout')
                }
                return Promise.reject(error)
            }
        )
    },
    render: h => h(App)
}).$mount('#app')


// const app = new Vue({
//     el: '#app',
// });
