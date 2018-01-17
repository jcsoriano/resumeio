@if(!isset($dontdeclareform) || !$dontdeclareform)
	<form id="form" action="{{ isset($action) ? url($action) : '' }}" method="POST" class="form-horizontal">
@endif
	{{ csrf_field() }}
	{{ isset($method) ? method_field($method) : '' }}
	@foreach($form as $name => $type)
		{!! Form::field($type, $resource, $name, $errors, [
			'wrapper' => 'form-horizontal',
			'id' => $id ?? 'new',
			'values' => $values ?? null,
			'disableOnEdit' => $disableOnEdit ?? null,
			'relationAttributes' => $relationAttributes ?? null,
			'fieldLabels' => $fieldLabels ?? null,
		]) !!}
	@endforeach

	@if(isset($revisions) && is_array($revisions) && count($revisions) > 1)
		<small class="col-sm-12 text-right">
			@include('magis.partials.revisions')
		</small>
	@endif

@if(!isset($dontdeclareform) || !$dontdeclareform)	
	<center>
		<button type="submit" class="btn btn-primary">Save</button>
		<a href="{{ isset($cancel) ? url($cancel) : url($resource.'/manage') }}" class="btn btn-default" >Cancel</a>
	</center>
@endif
@if(!isset($dontdeclareform) || !$dontdeclareform)
	</form>
@endif