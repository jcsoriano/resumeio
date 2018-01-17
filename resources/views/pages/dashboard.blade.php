@extends('magis.layouts.admin')

@section('header')
	<style>
		.metric-value {
			font-size:48px !important;
		}
	</style>
@endsection

@section('content')
	<section class="content">
		<div class="row">
			<div class="col-sm-9">
				<div class="row">
					<div v-for="(m, key) in metrics" class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-heading text-center" v-text="title(key)"></div>
							<div class="panel-body">
								<center>
									<h1 v-text="m" class="metric-value"></h1>
								</center>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h1>Daily Snapshots</h1>
						<line-graph id-modifier="daily-snapshots" chart-data="{{ json_encode($snapshots) }}" value-field="{{ json_encode($keys) }}"  :height="600" date-field="created_at" multi></line-graph>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h1>Real-time Trends</h1>
						<div v-if="realTimeTrend">
							<line-graph id-modifier="real-time-trends" :chart-data="realTimeTrend" value-field="{{ json_encode($keys) }}" :height="600" date-field="created_at" min-period="ss" multi></line-graph>
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-sm-3">
				<ul class="list-group">
					<li class="list-group-item" v-for="e in events" v-text="e"></li>
				</ul>
			</div>
		</div>
	</section>
@endsection

@section('before-foundation')
	<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
	<script src="//www.amcharts.com/lib/3/amcharts.js"></script>
	<script src="//www.amcharts.com/lib/3/serial.js"></script>
	<script src="https://www.amcharts.com/lib/3/pie.js"></script>
	<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
	<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
	<script src="https://www.amcharts.com/lib/3/themes/none.js"></script>
	<script>
		window.AmCharts = AmCharts;
	</script>
	<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection

@section('foundation')

	<script src="{{ asset('js/admin.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/vee-validate/2.0.0-beta.22/vee-validate.min.js"></script>
	<script>
		Vue.use(VeeValidate, {
	        errorBagName: 'validationErrors', // change if property conflicts.
	        fieldsBagName: 'validationFields', // change if property conflicts.
	    });
		new Vue({
		    el: '#app',
		    data: {
		    	metrics: {!! json_encode($metrics) !!},
		    	realTimeTrend: [],
		    	events: [],
		    },
		    mounted: function () {
		    	Echo.private('App.User.{{ $userId}}')

		    		// notifications
				    .notification(function (notification) {
				    	if (notification.type == 'App\\Notifications\\SomeoneRegistered') {
				    		++this.metrics.users;
				    		var entity = '';
				    		if (notification.is_company) {
				    			++this.metrics.companies;
				    			entity = 'Company';
				    		} else {
				    			++this.metrics.resumes;
				    			entity = 'User';
				    		}
				    		this.newEvent('A new ' + entity + ' Registered: ' + notification.name);
				    	}
				    }.bind(this))

				    // when a user verifies
				    .listen('UserVerified', function (event) {
				    	++this.metrics.user_verified;
				    	var entity = '';
				    	if (event.user.is_company) {
				    		++this.metrics.company_verified;
				    		entity = 'Company';
				    	} else {
				    		++this.metrics.individual_verified;
				    		entity = 'User';
				    	}
				    	this.newEvent('A new ' + entity + ' Verified: ' + event.user.name);
				    }.bind(this))

				    // when a job posting, job questionnaire, job application, 
				    // or job questionnaire answer is created
				    .listen('TrackedModelCreated', function (event) {
				    	var subject = '';
				    	var verb = '';
				    	if (event.class == 'App\\JobPosting') {
				    		++this.metrics.job_postings;
				    		subject = 'Job';
				    		verb = 'posted';
				    	} else if (event.class == 'App\\JobQuestionnaire') {
				    		++this.metrics.job_questionnaires;
				    		subject = 'Questionnaire';
				    		verb = 'created';
				    	} else if (event.class == 'App\\JobApplication') {
				    		++this.metrics.job_applications;
				    		subject = 'Application';
				    		verb = 'submitted';
				    	} else if (event.class == 'App\\JobQuestionnaireAnswer') {
				    		++this.metrics.job_questionnaire_answers;
				    		subject = 'Questionnaire';
				    		verb = 'answered';
				    	}
				    	this.newEvent('A ' + subject + ' was ' + verb);
				    }.bind(this));

				this.createSnapshot();
		    },
		    methods: {
		    	title: function (text) {
		    		return _.startCase(text);
		    	},

		    	newEvent(text) {
		    		this.events.push(text);
		    		$.growl.notice({
			        	title: 'Hurray!',
			        	message: text,
			        });
		    		this.createSnapshot();
		    		responsiveVoice.speak('Congratulations!' + text);
		    	},

		    	createSnapshot() {
		    		var snapshot = Object.assign({}, this.metrics);
		    		snapshot.created_at = moment().toDate();
		    		this.realTimeTrend.push(snapshot);
		    	},
		    },
		});
	</script>
@endsection
