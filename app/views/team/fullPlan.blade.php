@extends('layout')

@section('title')
	{{{ $team->name }}} 計劃書 -
@stop

@section('head')
	@parent
	<script>
		util.changeNavFocus(3);
	</script>
@stop

@section('guide')	
	<a href='{{ URL::to("team/" . $team->id) }}'><span class='guide-item guide-main'>{{{ $team->name }}}</span></a>
	<span class='guide-item guide-desc'>完整計劃書</span>
@stop

@section('content')
	<div class='smallRegionFrame'>
		<section class='region region-fullPlan'>
			<header class='region-header'>
				<h1 class='region-title yellow'>計劃書</h1>
				@if ($isLeader)
					<a class='btn btn-tran' href={{ URL::to("team/$team->id/edit") }}>修改</a>
				@endif
			</header>
			{{ View::make('reuse.fullPlan', ['team' => $team]) }}
		</section>
	</div>


@stop