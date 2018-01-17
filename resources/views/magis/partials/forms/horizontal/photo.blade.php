<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ title_case($name) }}</label>
		<div class="col-sm-10">
				<input 
					type="text" 
					class="form-control" 
					id="{{ $name }}" 
					name="{{ $name }}"
					placeholder="{{ ucfirst($name) }}" 
					value="{{ old($name) ?? $values[$name] ?? '' }}" 
					{!! isset($object) ? 'v-model="'.$object.'.'.$name.'"' : '' !!}
				>
				<font color="red">{{ $errors->first($name) }}</font>
		</div>
</div>