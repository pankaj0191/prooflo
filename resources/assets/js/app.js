
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */

require('./bootstrap');

import Vue from 'vue'

import store from './vuex/store';

Vue.config.ignoredElements = ['projects-container', 'proofer-container'];

Vue.config.productionTip = false;

if (process.env.MIX_ENV_MODE === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

Vue.component('register', require('./spa-modules/spa-projects/v3/Pages/Auth/Registration/Show'));


Spark.pluralTeamString = 'teams';

var app = new Vue({
    store,
    // mixins: [require('spark')],
});