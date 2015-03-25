@extends('layout')

@section('head')
	@parent
	<script>
		util.changeNavFocus(3);
		
		util.setScalableWidth('.team-section', 120);
	</script>	
@stop

@section('guide')	
	@if ($public)
	<a href='{{ URL::to("team/") }}'><span class='guide-item guide-main'>社內隊伍</span></a>
	<a class='btn btn-tran2 guide-item guide-btn' href={{ URL::to('privateTeam') }}>> 老人隊伍</a>
	@else
	<a href='{{ URL::to("privateTeam/") }}'><span class='guide-item guide-main'>老人隊伍</span></a>
	<a class='btn btn-tran2 guide-item guide-btn' href={{ URL::to('team/') }}>> 社內隊伍</a>
	@endif
	<a class='btn btn-tran2 guide-item guide-btn' href={{ URL::to('team/create') }}
		><span class='lsf'>plus</span> 新增</a>
	
	
@stop

@section('content')
	@if ($teams->count() != 0)
	<section class='team-section center'>
		@foreach ($teams as $team)
			{{ View::make('reuse.team', ['team' => $team]); }}
		@endforeach		
	</section>
	@endif
	
@stop