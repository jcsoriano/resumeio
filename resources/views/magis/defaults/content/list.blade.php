<?php $resource = $resource ?? Request::segment(1); ?>

@extends('magis.layouts.public')

@section('cover')
	@include('magis.partials.samples.cover')
@endsection

@section('content')
	@include('magis.defaults.content.partials.list')
@endsection

@section('foundation')
	@include('magis.defaults.content.partials.list-foundation')
@endsection