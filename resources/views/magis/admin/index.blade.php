@extends('magis.layouts.admin')

@section('content')
	@include('magis.partials.contentheader')
	<section class="content">
		<crud 
			resource="{{ $resource }}"
			type-map="{{ json_encode($typeMap ?? []) }}"
			title="{{ $title ?? '' }}" 
			collection-type="table" 
			per-page-class="col-sm-4" 
			search-class="col-sm-4 col-sm-offset-4" 
			status-class="col-sm-6" 
			pagination-class="col-sm-6" 
			disable-on-edit="{{ json_encode($disableOnEdit ?? []) }}" 
			title-buttons="{{ json_encode($titleButtons ?? []) }}" 
			{{ isset($noAdd) && $noAdd ? 'no-add' : '' }} 
			source-url="{{ $sourceUrl ?? $resource.'/manage' }}" 
			row-buttons="{{ json_encode($buttons ?? []) }}" 
			field-labels="{{ json_encode($fieldLabels ?? []) }}" 
			{{ isset($redirectAfterQuickAdd) && $redirectAfterQuickAdd ? 'redirect-after-quick-add' : '' }} 
			{{ isset($noFull) && $noFull ? 'no-full' : '' }}
			current-user="{{ Auth::user()->toJson() }}"
			field-labels="{{ json_encode($fieldLabels ?? []) }}"
			form="{{ json_encode($form ?? []) }}"
			relation-attributes="{{ json_encode($relationAttributes ?? []) }}"
			permissions="{{ json_encode($permissions ?? []) }}"
			columns="{{ json_encode($columns ?? []) }}"			
			display-columns="{{ json_encode($displayColumns ?? []) }}"
			real-time-columns="{{ json_encode($realTimeColumns ?? []) }}"
		>
		</crud>
	</section>
@endsection

@section('scripts')
<script>
	$('.slim-scroll').slimScroll();
</script>
@endsection