@extends('magis.layouts.admin')

@section('content')
	<section class="content">
		{{-- <line-graph chart-data="{{ json_encode($data) }}"></line-graph> --}}

		{{-- <line-graph chart-data="{{ json_encode($data) }}" value-field="{{ json_encode(['italy', 'germany', 'uk']) }}" date-field="year" multi></line-graph> --}}

		{{-- <bar-graph chart-data="{{ json_encode($data) }}" category-field="country" value-field="visits" value-title="Visitors"></bar-graph> --}}

		{{-- <bar-graph chart-data="{{ json_encode($data) }}" category-field="year" value-field="{{ json_encode(['income', 'expenses']) }}" value-title="{{ json_encode(['income', 'expenses']) }}" multi></bar-graph> --}}

		<pie-chart chart-data="{{ json_encode($data) }}" title-field="country" ></pie-chart>

	</section>
@endsection

@section('before-foundation')
	<script src="//www.amcharts.com/lib/3/amcharts.js"></script>
	<script src="//www.amcharts.com/lib/3/serial.js"></script>
	<script src="https://www.amcharts.com/lib/3/pie.js"></script>
	<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
	<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
	<script src="https://www.amcharts.com/lib/3/themes/none.js"></script>
	<script>
		window.AmCharts = AmCharts;
	</script>
@endsection