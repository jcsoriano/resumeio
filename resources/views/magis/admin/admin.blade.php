@extends('magis.layouts.admin')

@section('header')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
	<section>
		<div class="row">
			<div class="col-sm-4">
				<crud resource="roles" type="list"></crud>
			</div>
			<div class="col-sm-4">
				<crud resource="users" type="list"></crud>
			</div>
			<div class="col-sm-4"></div>
		</div>
	</section>
@endsection

@section('scripts')
<script>
	Dropzone.autoDiscover = false;
	$('.slim-scroll').slimScroll();
</script>
@endsection