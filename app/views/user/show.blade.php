@extends('layout')

@section('head')
	@parent
	<script>
		$(document).ready(function() {
			$('.guide, footer').hide();
			$('body').css('background', '#00c0b2');
		});
	</script>
	
@stop


@section('content')
	<div class='userBasicInfo'>
		<section class='userFrame'>
			<h1 class='title-l title-personal'>個人資訊</h1>
			
			<p>
				<img class='userFrame-profile' src={{ asset($user->profileSrc) }}>
			</p>
			
			@if (Auth::id() == $user->id)	
				<a class='btn btn-yellow2' href={{ URL::to('user/' . $user->id . '/edit') }}>修改基本資料</a>
			@endif
			
			<p>
				<table>
					<tr>
						<td>暱稱</td><td>{{ $user->name }}</td>
					</tr>					
				@if (Auth::check())
					<tr>
						<td>電子信箱</td><td>{{ $user->email }}</td>
					</tr>
					<tr>
						<td>真實姓名</td><td>{{ $user->realName }}</td>
					</tr>
					<tr>
						<td>系級</td><td>{{ $user->grade }}</td>
					</tr>
				@endif
					<tr>
						<td>文章數量</td><td>{{ $user->articles->count() }}</td>
					</tr>
					<tr>
						<td>隊伍數量</td><td>{{ $user->teams->count() }}</td>
					</tr>
					<tr>
						<td>上站次數</td><td></td>
					</tr>
					<tr>
						<td>註冊日期</td><td>{{ $user->created_at }}</td>
					</tr>
					<tr>
						<td>上次登入</td><td></td>
					</tr>
				</table>
			</p>
			@if (Auth::id() == $user->id)
				<p class='desc yellow3'>* 真實姓名及系級僅顯示給已註冊會員，請盡量填寫以利隊伍名單完整，方便審隊</p>
				
				<h1 class='title-l title-personal'>個人訊息</h1>
				<p>
				@foreach($user->messages as $message)
					{{ View::make('reuse.media.message', ['media' => $message]) }}
					<p>
				@endforeach
			@endif
		</section>
	</div>
	<div class='userAdvancedInfo'>
		<h1 class='title-l title-personal'>參與隊伍</h1>
		@foreach ($user->teams as $index => $team)
			<div class='media media-article' onclick="util.link('{{ URL::to("team/" . $team->id) }}')">
		
				<div class='media-item media-indexFrame'>
					<span class='media-item media-index'>{{ $index + 1 }}</span>
				</div>
				
				<div class='media-item media-color bck-white'></div>
								
				
				{{ View::make('reuse.user', ['user' => $team->leaders()->first()]) }}				
				
				<img class='media-item media-img' src='{{ asset($team->profileSrc) }}'>
				
				<div class='media-item media-period'>
					<time>{{ $team->startDate }} <br> ~ {{ $team->backDate }}</time>
				</div>
				<span class='media-item media-title gray'><span>{{{ $team->name }}}</span></span>
			</div>
		@endforeach
	</div>
	
@stop