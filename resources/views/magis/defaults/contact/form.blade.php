@extends('magis.layouts.public')

@section('cover')
	<?php 
		$item = new stdClass;
		$item->name = 'Contact';
		$item->excerpt = 'MagisSolutions Inc';
	?>

	@include('magis.partials.samples.cover')
@endsection

@section('content')
	<div id="app" class="container">
		<form method="POST" action="{{ url('contact') }}" class="col-sm-8 col-sm-offset-2">
			{{ csrf_field() }}
			{!! Form::string('contact', 'name', $errors, ['wrapper' => 'form-vertical']) !!}
			{!! Form::email('contact', $errors, ['wrapper' => 'form-vertical']) !!}
			{!! Form::string('contact', 'contact_number', $errors, ['wrapper' => 'form-vertical']) !!}
			{!! Form::text('contact', 'message', $errors, ['wrapper' => 'form-vertical']) !!}
			<button type="submit" class="btn btn-primary">Send Message</button>
		</form>
	</div>
	<br/>
	<br/>
@endsection