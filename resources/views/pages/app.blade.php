@extends('layouts.resume')

@section('content')
	<main-app 
        :value="value"
        :left-navigation-links="leftNavigationLinks"
        :right-navigation-links="rightNavigationLinks"
        :action-point-options="actionPointOptions"
        sync-url="{{ url()->current() }}"
        sync-url-segment="{{ str_replace(url('/').'/', '', url()->current()) }}"
        slug="{{ $slug }}"
        :init-show-options="{{ isset($editing) && $editing ? 'true' : 'false' }}"
        {{ isset($editing) && $editing ? 'editing' : '' }}
        {!! isset($name) && !empty($name) ? 'name="' . $name . '"' : '' !!}
        {!! isset($intent) && !empty($intent) ? 'intent="' . $intent . '"' : '' !!}
        {!! isset($applyTo) && !empty($applyTo) ? 'apply-to="' . $applyTo . '"' : '' !!}
        {!! isset($introModal) && !empty($introModal) ? 'intro-modal="' . $introModal . '"' : '' !!}
        {!! Auth::check() ? 'logged-in' : '' !!}
        {!! Auth::check() ? 'user-home="' . Auth::user()->resumes()->first()->slug . '"' : '' !!}
    ></main-app>
@endsection

@section('main-script')
    <script type="text/javascript" src="{{ asset('js/main-app.js') }}"></script>
    @if((isset($isCompany) && $isCompany) || (Auth::check() && Auth::user()->is_company))
        <script type="text/javascript" src="{{ asset('js/company.js') }}"></script>
    @endif
    @if(isset($questionnaire) && $questionnaire)
        <script type="text/javascript" src="{{ asset('js/questionnaire.js') }}"></script>
    @endif
    @if(!Auth::check())
        <script type="text/javascript" src="{{ asset('js/landing.js') }}"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/vee-validate/2.0.0-beta.22/vee-validate.min.js"></script>

    <script>
        Vue.use(VeeValidate, {
            errorBagName: 'validationErrors', // change if property conflicts.
            fieldsBagName: 'validationFields', // change if property conflicts.
        });
        
        new Vue({
    	    el: '#app',
    	    data: {
                value: {!! json_encode($resume) !!},
                actionPointOptions: {!! json_encode($actionPointOptions) !!},
                leftNavigationLinks: {!! json_encode($leftNavigationLinks) !!},
                rightNavigationLinks: {!! json_encode($rightNavigationLinks) !!},
    	    },

            mounted() {

            },

            methods: {

            },
    	});
	</script>
@endsection
