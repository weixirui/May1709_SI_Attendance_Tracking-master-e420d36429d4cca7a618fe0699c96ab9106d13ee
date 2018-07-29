
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./index.js');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('session', require('./components/session.vue'));
Vue.component('session_table', require('./components/sessionTable.vue'));

const app = new Vue({
    el: '#app',
    // I can't think of any application wide vue data we need...
    // but it would go here.
    data: function(){ 
        return {
            // I bet I could do this a better way than defining strings at the root... 
            // [how do you pass in string literals to vue components]
            upcoming: "Upcoming",
            completed: "Completed",
            processed: "Processed"
        }
    }
});
