
/**
 * Load Public, as it is a subset of Admin scripts
 */
require('./admin');

/**
 * Load your admin-specific Vue components here
 */
Vue.component('select-users', require('./components/magis/Pages/Role/SelectUsers.vue'));
Vue.component('select-permissions', require('./components/magis/Pages/Role/SelectPermissions.vue'));