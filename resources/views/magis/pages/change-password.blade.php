@extends('magis.layouts.admin')

@section('content')
	@include('magis.partials.contentheader')
	<section class="content">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<form id="form" action="{{ isset($action) ? url($action) : '' }}" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					{{ method_field('PUT') }}

					<?php $fields = ['old_password', 'new_password', 'new_password_confirmation']; ?>

					@foreach($fields as $f)
						{!! Form::password($resource, $errors, [
							'wrapper' => 'form-horizontal',
							'inputWrapperClass' => 'col-sm-8',
							'labelClass' => 'col-sm-4',
							'fieldLabels' => [
								'new_password_confirmation' => 'Confirm New Password',
							],
						], $f) !!}
					@endforeach

					<center>
						<button type="submit" class="btn btn-primary">Save</button>
						<a href="{{ isset($cancel) ? url($cancel) : url($resource.'/manage') }}" class="btn btn-default" >Cancel</a>
					</center>
				</form>
			</div>
		</div>
	</section>
@endsection