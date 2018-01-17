
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./public-bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('default', require('./components/magis/ListTypes/Default.vue'));
Vue.component('magis-default', require('./components/magis/CollectionTypes/Default.vue'));
Vue.component('magis-text', require('./components/magis/FieldTypes/Text.vue'));
Vue.component('magis-string', require('./components/magis/FieldTypes/String.vue'));
Vue.component('form-vertical', require('./components/magis/FieldLayouts/Vertical.vue'));
Vue.component('collection', require('./components/magis/Collection.vue'));
Vue.component('collapsible', require('./components/magis/Collapsible.vue'));
Vue.component('magis-img', require('./components/magis/Image.vue'));
