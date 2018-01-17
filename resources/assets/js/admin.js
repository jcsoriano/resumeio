
/**
 * Load Public, as it is a subset of Admin scripts
 */
require('./public');
require('./admin-bootstrap');

/**
 * Load your admin-specific Vue components here
 */
Vue.component('draggable', require('vuedraggable'));
Vue.component('magis-mediumText', require('./components/magis/FieldTypes/MediumText.vue'));
Vue.component('magis-many-relation', require('./components/magis/FieldTypes/ManyRelation.vue'));
Vue.component('magis-one-relation', require('./components/magis/FieldTypes/OneRelation.vue'));
Vue.component('magis-boolean', require('./components/magis/FieldTypes/Boolean.vue'));
Vue.component('magis-datetime', require('./components/magis/FieldTypes/DateTime.vue'));
Vue.component('magis-timerange', require('./components/magis/FieldTypes/TimeRange.vue'));
Vue.component('magis-photo', require('./components/magis/FieldTypes/Photo.vue'));
Vue.component('form-horizontal', require('./components/magis/FieldLayouts/Horizontal.vue'));
Vue.component('line-graph', require('./components/magis/ChartTypes/Line.vue'));
Vue.component('bar-graph', require('./components/magis/ChartTypes/Bar.vue'));
Vue.component('pie-chart', require('./components/magis/ChartTypes/Pie.vue'));
Vue.component('modal', require('./components/magis/Modal.vue'));
Vue.component('form-modal', require('./components/magis/FormModal.vue'));
Vue.component('archive', require('./components/magis/Archive.vue'));
Vue.component('string', require('./components/magis/ListTypes/String.vue'));
Vue.component('magis-list', require('./components/magis/CollectionTypes/List.vue'));
Vue.component('collection', require('./components/magis/Collection.vue'));
Vue.component('real-time', require('./components/magis/RealTime.vue'));
Vue.component('tr-content', require('./components/magis/RowTypes/Content.vue'));
Vue.component('magis-table', require('./components/magis/CollectionTypes/Table.vue'));
Vue.component('crud', require('./components/magis/Crud.vue'));
Vue.component('settings', require('./components/magis/Settings.vue'));
