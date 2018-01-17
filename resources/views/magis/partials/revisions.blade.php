<collapsible>
	<a href="#" @click.prevent slot="showTrigger">
		Show revisions <span class="glyphicon glyphicon-triangle-bottom"></span>
	</a>
	<div slot="content">
		<?php $key = $current->getRouteKeyName(); ?>
		<a href="{{ url($resource.'/'.$current->$key.'/edit') }}">
			Current by
			{{ $current->username }} 
			{{ Carbon\Carbon::parse($current->updated_at)->diffForHumans() }}
		</a>
		<br/>
		<?php $original = array_pop($revisions); ?>
		<?php $i = 0; ?>
		@foreach($revisions as $r)
			<a href="{{ url($resource.'/'.$r->revision_id.'/revert') }}">
				@if(isset($r->deleted_at) && empty($r->deleted_at) && isset($revisions[$i+1]) && !empty($revisions[$i+1]->deleted_at))
					<!-- older !empty deleted -->
					Restored by
				@elseif(isset($r->deleted_at) && $r->updated_at == $r->deleted_at)
					Deleted by 
				@elseif(isset($r->is_draft) && isset($revisions[$i+1]) && $revisions[$i+1]->is_draft != $r->is_draft)
					@if($r->is_draft == '1')
						Drafted by 
					@else
						Published by
					@endif
				@else
					Revised by 
				@endif
				{{ $r->username }} 
				{{ Carbon\Carbon::parse($r->updated_at)->diffForHumans() }}
			</a>
			<br/>
			<?php ++$i; ?>
		@endforeach
		<a href="{{ url($resource.'/'.$original->revision_id.'/revert') }}">
			Original by
			{{ $original->username }} 
			{{ Carbon\Carbon::parse($original->updated_at)->diffForHumans() }}
		</a>
	</div>
	<a href="#" @click.prevent slot="hideTriggerAfter">
		Hide revisions <span class="glyphicon glyphicon-triangle-top"></span>
	</a>
</collapsible>