@extends('magis.layouts.public')

@section('cover')
	<?php 
		$item = new stdClass;
		$item->name = 'Thanks!';
		$item->excerpt = Settings::value('site-title');
	?>

	@include('magis.partials.samples.cover')
@endsection

@section('content')
	<div id="app" class="container">
		<center>
			<p>Thank you for contacting {{ Settings::value('site-title') }}, expect a reply soon!</p>
		</center>
	</div>
	<br/>
	<br/>
@endsection