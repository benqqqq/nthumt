@extends('layout')

@section('title')
	[{{{ $article->category->name }}}] {{{ $article->title }}} -
@stop

@section('head')
	@parent
	<script>
		function submitComment(form) {
			function success(res) {				
				console.log(res);
				if (res != '') {
					popper.showWithTitleMessage('Oops !', res);
				} else {
					location.reload();	
				}
			}
			util.ajaxPost('{{ URL::to("article/" . $article->id . "/comment") }}', form, success);
			return false;
		}
		
		
		util.changeNavFocus(4);
		util.setScalableWidth('.article-main, .article-comment', 120);
		
		$(document).ready(function(){
			$('footer').hide();
		});
		
	</script>
@stop

@section('guide')	
<?php 
	if ($article->team != null) {
		$backUrl = 'team/' . $article->team->id;
	} else {
		$backUrl = 'article';
	}
?>
	
	
		
	@if ($article->category->id == Category::teamCategory()->id)
		<a href='{{ URL::to("team/" . $article->team->id) }}'>
			<span class='guide-item guide-main'>{{{ $article->team->name }}}</span>
		</a>
	@else
		<a href='{{ URL::to("article?zone_id=" . $article->zone->id) }}'>
			<span class='guide-item guide-main'>{{{ $article->zone->name }}}</span>
		</a>
	@endif
	@if ($article->hasTeam())
		@if ($article->isAuthor())
			<a class='guide-item guide-btn btn btn-tran2' href={{ URL::to('team/' . $article->fullPlanTeam->id . '/edit') }}>編輯</a>
		@endif
		<a href={{ Url::to('team/' . $article->fullPlanTeam->id . '/fullPlan') }}
			class='guide-item guide-btn btn btn-tran2'>前往隊伍計劃書</a>
		<a href={{ Url::to('team/' . $article->fullPlanTeam->id) }} class='guide-item guide-btn btn btn-tran2'>前往隊伍</a>
		<span class='guide-item guide-desc-right'>(此文章由系統自動產生)</span>
	@else
		@if ($article->isAuthor())
			<a class='guide-item guide-btn btn btn-tran2' onclick="popper.warn(
				'{{ URL::to("article/" . $article->id . "/delete/?backUrl=" . $backUrl) }}', '確定刪除 ? 此動作無法復原')">刪除</a>
			<a class='guide-item guide-btn btn btn-tran2' href={{ URL::to('article/' . $article->id . '/edit') }}>編輯</a>
		@endif
	@endif
@stop

@section('content')
	<section class='article-main center'>
	
		<header class=' article-main-header'>		
				{{ View::make('reuse.user', ['user' => $article->user]) }}
				<span class='article-main-header-item article-main-header-category'>{{{ $article->category->name }}}</span>				
				<time class='article-main-header-item article-main-header-time'>{{ $article->created_at }}</time>
				<span class='article-main-header-item article-main-header-title'><span class='article-main-header-title-span'>{{{ $article->title }}}<span></span>
		</header>
		
		<article class='article-main-content'>
			<div class='article-main-content-frame'>
				@if ($article->hasTeam())
					{{ $article->content }}
				@else
					{{ Mylib\Util::normal2br(Mylib\Util::purify($article->content)) }}
				@endif
			</div>
		</article>
		@if ($article->updated_at != $article->created_at)
			<article class='article-main-editTime'>
				最後編輯 : {{ $article->updated_at }}
			</article>
		@endif
	</section>
	
	<section class='article-comment center'>				
		@foreach ($article->comments as $comment)
			{{ View::make('reuse.media.comment', ['media' => $comment, 'class' => 'rightest']) }}
		@endforeach
		
		@if (Auth::check())
			{{ Form::open(['url' => 'article/' . $article->id . '/comment', 'method' => 'post', 
				'onsubmit' => 'return submitComment(this)']) }}
				<div class='media media-comment'>								
					{{ View::make('reuse.user', ['user' => Auth::user()]) }}				
					<div class='media-item media-submit'>
						{{ Form::submit('送出', ['class' => 'btn btn-red']) }}
					</div>					
					<div class='media-item media-content'>
					{{ Form::text('content', null, ['placeholder' => '留言', 'class' => 'media-comment-input', 
						'autocomplete' => 'off']) }}
					</div>
				</div>
			{{ Form::close() }}
		@endif
	</section>

@stop