@extends('magis.layouts.public')

@section('cover')
	@include('magis.partials.samples.cover')
@endsection

@can('update', $item)
	@section('admin-bar')
		@include('magis.partials.admin-bar')
	@endsection
@endcan

@section('content')
	@include('magis.defaults.content.partials.view')
@endsection

@can('update', $item)
	@section('script')
		@include('magis.partials.admin-bar-script')
	@endsection
@endcan