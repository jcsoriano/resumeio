<script src="{{ asset('js/admin.js') }}"></script>
<script src="https://cdn.jsdelivr.net/vee-validate/2.0.0-beta.22/vee-validate.min.js"></script>
<script>
	Vue.use(VeeValidate, {
        errorBagName: 'validationErrors', // change if property conflicts.
        fieldsBagName: 'validationFields', // change if property conflicts.
    });
	new Vue({
	    el: '#app'
	});
</script>