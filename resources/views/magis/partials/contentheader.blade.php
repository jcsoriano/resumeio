<section class="content-header">
	<h1>
		{{ $header['header'] }}
		<small>{{ $header['small'] ?? '' }}</small>
	</h1>
	<ol class="breadcrumb">
		@foreach($header['breadcrumbs'] as $bc)
			<li><a href="{{ url($bc['link']) }}"><i class="{{ $bc['icon'] }}"></i> {{ $bc['name'] }}</a></li>
		@endforeach
	</ol>
</section>