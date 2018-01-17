
require('./app-bootstrap');

// var VueSmoothScroll = require('vue-smoothscroll');
// Vue.use(VueSmoothScroll);

Vue.component('draggable', require('vuedraggable'));
Vue.component('modal', require('./components/magis/Modal.vue'));
Vue.component('magis-img', require('./components/magis/Image.vue'));
Vue.component('magis-photo', require('./components/magis/FieldTypes/Photo.vue'));
Vue.component('magis-text', require('./components/magis/FieldTypes/Text.vue'));
Vue.component('magis-string', require('./components/magis/FieldTypes/String.vue'));
Vue.component('v-star-rating', require('vue-star-rating'));
Vue.component('star-rating', require('./components/magis/FieldTypes/StarRating.vue'));
Vue.component('main-app', require('./components/MainApp.vue'));

Vue.component('navigation-links', require('./components/resume/NavigationLinks.vue'));
Vue.component('banner-picture', require('./components/resume/BannerPicture.vue'));
Vue.component('profile-picture', require('./components/resume/ProfilePicture.vue'));
Vue.component('contact', require('./components/resume/Contact.vue'));
Vue.component('mini-profile', require('./components/resume/MiniProfile.vue'));
Vue.component('snapshot', require('./components/resume/Snapshot.vue'));
Vue.component('snapshot-items', require('./components/resume/SnapshotItems.vue'));

Vue.component('gallery-section', require('./components/resume/SectionTypes/GallerySection.vue'));
Vue.component('link-section', require('./components/resume/SectionTypes/LinkSection.vue'));
Vue.component('paragraph-section', require('./components/resume/SectionTypes/ParagraphSection.vue'));
Vue.component('bullet-section', require('./components/resume/SectionTypes/BulletSection.vue'));
Vue.component('rating-section', require('./components/resume/SectionTypes/RatingSection.vue'));

Vue.component('gallery-item', require('./components/resume/ResumeItemTypes/GalleryItem.vue'));
Vue.component('gallery-item-default', require('./components/resume/ResumeItemTypes/GalleryItemDefault.vue'));
Vue.component('link-item', require('./components/resume/ResumeItemTypes/LinkItem.vue'));
Vue.component('bullet-item', require('./components/resume/ResumeItemTypes/BulletItem.vue'));
Vue.component('rating-item', require('./components/resume/ResumeItemTypes/RatingItem.vue'));

Vue.component('sections', require('./components/resume/Sections.vue'));