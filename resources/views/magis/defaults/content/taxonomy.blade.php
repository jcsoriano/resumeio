<?php $resource = $resource ?? Request::segment(1); ?>

@extends('magis.layouts.public')

@section('cover')
	@include('magis.partials.samples.cover')
@endsection

@can('manage-'.$resource)
    @section('admin-bar')
	   @include('magis.partials.admin-bar')
    @endsection

    @if(isset($items->content)) 
        @section('script')
           @include('magis.partials.admin-bar-script')
        @endsection
    @endif
@endcan

@section('content')
    @if(isset($items->content)) 
        @include('magis.defaults.content.partials.view')
    @endif
    <?php var_dump($items); ?>
    @if(count($items) > 0)
        @include('magis.defaults.content.partials.list', ['resource' => $mainResource])
    @endif
@endsection