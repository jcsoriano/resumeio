@extends('magis.layouts.admin')

@section('content')
	<section class="content">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<center>
					<h3>{{ $subject }}</h3>
					<p>{{ $message }}</p>
				</center>
			</div>
		</div>
	</section>
@endsection