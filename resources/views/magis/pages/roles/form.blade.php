@extends('magis.layouts.admin')

@section('content')
	@include('magis.partials.contentheader')

	<section class="magis-form">
		<form action="{{ url($action) }}" method="POST">
			<div class="row">
				<div class="col-sm-offset-2 col-sm-8">
					@include('magis.partials.form')
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-sm-8">
					<select-permissions permissions="{{ json_encode($permissions) }}" options="{{ json_encode($options) }}" role-permissions="{{ json_encode($rolePermissions) }}">
					</select-permissions>
				</div>
				<div class="col-sm-4">
					<select-users :role-name="role.name" users="{{ json_encode($users) }}" role-users="{{ json_encode($roleUsers) }}" mode="{{ $mode }}">
					</select-users>
				</div>
			</div>
			<div class="row magis-form">
				<center>
					<button type="submit" class="btn btn-primary">Save</button>
					<a href="{{ url('roles/manage') }}" class="btn btn-default">Cancel</a>
				</center>
			</div>
		</form>
	</section>
@endsection

@section('foundation')
	<script src="{{ asset('js/role.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/vee-validate/2.0.0-beta.22/vee-validate.min.js"></script>
	<script>
		Vue.use(VeeValidate, {
	        errorBagName: 'validationErrors', // change if property conflicts.
	        fieldsBagName: 'validationFields', // change if property conflicts.
	    });
		new Vue({
			el: '#app',
			data: {
				<?php
					if($values) {
						$role = $values;
					} else {
						$copy = $form;
						array_walk($copy, function(&$val) { 
							$val = ''; 
						});
						$role = $copy;
					}
				?>
				role: {!! json_encode($role) !!}
			}
		});
	</script>
@endsection

@section('scripts')
	<script>
		$('.slim-scroll').slimScroll();
	</script>
@endsection