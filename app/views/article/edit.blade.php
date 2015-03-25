@extends('layout')

@section('title')
	[{{{ $article->category->name }}}] {{{ $article->title }}} -
@stop

@section('head')	
	@parent
	{{ HTML::script('js/Checker.js') }}
	{{ HTML::script('js/angular/articleCtrl.js') }}	
	
	<script>
		
		util.changeNavFocus(4);
		
		var categories = {{ $categories }};
		var categoryIndex = 4;
		
		util.setScalableWidth('.article-main, .article-comment, .article-category', 120);
		
		function changeCategory(i) {
			if (!isDefaultContent() && categories[i].content != '') {
				popper.categoryWarn(i);
			} else {
				changeCategoryName(i);
				changeCategoryContent(i);
			}						
		}
		function isDefaultContent() {
			return ($('#content').val() == categories[categoryIndex].content);
		}
		function changeCategoryName(i) {
			$('.article-main-header-category').html(categories[i].name);
			$('#category').val(categories[i].id);
			$('.article-category ul li.btn-red').removeClass('btn-red').addClass('btn-gray');
			$('.article-category ul li:nth-child(' + (i+2) + ')').removeClass('btn-gray').addClass('btn-red');
			categoryIndex = i;
		}
		function changeCategoryContent(i) {
			if (categories[i].content != '') {
				$('#content').val(categories[i].content);	
			}
		}		
		
		function textAreaAdjust(obj) {
			if (obj.scrollHeight > obj.clientHeight) {
				obj.style.height = "1px";
				obj.style.height = (15 + obj.scrollHeight)+"px";
			}
		}
		
		$(document).ready(function() {
			$('#content').keyup();
			$('footer').hide();
		});
	</script>
	
@stop

@section('guide')	
	@if ($article->category->id == Category::teamCategory()->id)
		<a href='{{ URL::to("team/" . $article->team->id) }}'>
			<span class='guide-item guide-main'>{{{ $article->team->name }}}</span>
		</a>
	@else
		<a href='{{ URL::to("article?zone_id=" . $article->zone->id) }}'>
			<span class='guide-item guide-main'>{{{ $article->zone->name }}}</span>
		</a>
	@endif
	<span class='guide-item guide-desc'>修改文章</span>
@stop

@section('content')
	
	@if ($article->category->name != '隊伍')
		<section class='article-category center clearfix'>
			<ul class='center'>
			<?php $i = 0?>
				<li><span class='desc'>更換分類 : </span></li>
			@foreach ($categories as $category)		
				<li class='btn btn-gray' onclick="changeCategory({{ $i++ }})">{{ $category->name }}</li>
			@endforeach
			</ul>
		</section>
	@endif
	
	<section class='article-main center' ng-controller='articleCtrl'>	
		<form method='post' id='dataForm' ng-submit="submitForm('{{ URL::to('article/' . $article->id . '/edit') }}')">
			<header class='center clearfix article-main-header'>		
					{{ View::make('reuse.user', ['user' => $article->user]) }}
					<span class='article-main-header-item article-main-header-category'>{{ $article->category->name }}</span>
					<time class='article-main-header-item article-main-header-time'>{{ $article->created_at }}</time>
					<span class='article-main-header-item article-main-header-title'>{{ Form::text('title', $article->title,
						['id' => 'title']) }}<span class='red warn' hidden="true">標題不可空白</span></span>
					
			</header>
			
			<article class='center article-main-content'>
				{{ Form::textarea('content', $article->content, ['id' => 'content', 'onkeyup' => 'textAreaAdjust(this)']) }}
			</article>				
			{{ Form::hidden('category_id', $article->category->id, ['id' => 'category']) }}
			<div class='article-main-submit center'>
				{{ Form::submit('送出', ['class' => 'btn btn-red']) }}
			</div>
			{{ Form::close() }}	
	</section>

	
	<section class='article-comment center'>				
		@foreach ($article->comments as $comment)
			{{ View::make('reuse.media.comment', ['media' => $comment, 'class' => 'rightest']) }}
		@endforeach
	</section>
	
@stop