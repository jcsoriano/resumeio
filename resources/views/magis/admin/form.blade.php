@extends('magis.layouts.admin')

@section('content')
	@include('magis.partials.contentheader')
	<section class="content">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				@include('magis.partials.form')
			</div>
		</div>
	</section>
@endsection