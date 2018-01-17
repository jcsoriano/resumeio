window.App = {
    baseUrl: document.getElementById('homepage').getAttribute('content'),
    csrfToken: document.getElementById('csrf-token').getAttribute('content'),
    error(error) {
        var message = 'Something went wrong. Please check your internet connection and reload the page.';
        if (error.status == 0) {
            message = 'You do not seem to be connected to the internet. Please check your connection and reload the page.';
        } else if (error.status == 401) {
            message = 'You are not or no longer logged-in. Please reload this page and log-in.';
        } else if (error.status == 403) {
            message = 'You are not authorized to do this action.';
        } else if (error.status == 422) {
            message = 'One or more input fields have an error. Please check if all required fields have been filled-up.';
        }

        $.growl.error({ 
            title: 'Error',
            message: message,
        });
        console.log(error);
    },
};

window._ = require('lodash');

window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('photoswipe');

require('jquery.growl');

window.Vue = require('vue');
require('vue-resource');

window.Dropzone = require('dropzone');

// window.moment = require('moment');
// require('timepicker');
// require('bootstrap-datepicker');

// window.micromustache = require('micromustache');

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */
Vue.http.options.root = App.baseUrl + '/api';
Vue.http.interceptors.push((request, next) => {
	request.headers.map['X-CSRF-TOKEN'] = [App.csrfToken];
    next();
});

window.url = function (urlString) {
	if (!urlString) {
        return false;
    }

    if (urlString.substr(0, 4) == 'http' || urlString.substr(0, 2) == '//') {
        return urlString;
    }

    return App.baseUrl + '/' + urlString;
};

window.humanDate = function (date, withDayOfWeek) {
	var format = (withDayOfWeek ? 'dddd, ' : '') + 'MMMM DD, YYYY';
	return moment(date).format(format);
};