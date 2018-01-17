<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ title_case($name) }}</label>
    <div class="col-sm-10">
      	<textarea 
      		class="form-control" 
      		id="{{ $name }}" 
      		name="{{ $name }}"
      		placeholder="{{ ucfirst($name) }}" 
      		{!! isset($object) ? 'v-model="'.$object.'.'.$name.'"' : '' !!}
      	>
      		{{ old($name) ?? $values[$name] ?? '' }}
      	</textarea>
    </div>
</div>