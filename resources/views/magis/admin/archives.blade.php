@extends('magis.layouts.admin')

@section('content')
	@include('magis.partials.contentheader')
	<section class="content">
		<archive resource="{{ $resource }}" type="table" per-page-class="col-sm-4" search-class="col-sm-4 col-sm-offset-4" status-class="col-sm-6" pagination-class="col-sm-6" ></archive>
	</section>
@endsection

@section('scripts')
<script>
	$('.slim-scroll').slimScroll();
</script>
@endsection