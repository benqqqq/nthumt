@extends('layout')

@section('title')
	新增 -
@stop

@section('head')
	@parent
	{{ HTML::script('js/Checker.js') }}
	{{ HTML::script('js/angular/articleCtrl.js') }}
	
	<script>
		util.changeNavFocus(4);				
		var categories = {{ $categories }};
		var categoryIndex = 5;
		
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
				
		$(document).ready(function(){
			@if (!Input::has('team_id'))
				changeCategory(categoryIndex);			
			@endif
			$('#content').keyup();
			$('footer').hide();
		});
	</script>
	
@stop

@section('guide')	
	<span class='guide-item guide-desc'>發表文章</span>
@stop

@section('content')
	@if (!Input::has('team_id'))	
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
		<form method='post' id='dataForm' ng-submit="submitForm('{{ URL::to('article/create') }}')">
			<header class='clearfix article-main-header'>		
					{{ View::make('reuse.user', ['user' => Auth::user()]) }}
					<span class='article-main-header-item article-main-header-category'>隊伍</span>
					<span class='article-main-header-item article-main-header-title'>{{ Form::text('title', null, 
						['placeholder' => '標題', 'id' => 'title']) }}
						<span class='red warn' hidden="true">標題不可空白</span></span>
					
			</header>
			
			<article class='article-main-content'>
				{{ Form::textarea('content', null, 
					['id' => 'content', 'onkeyup' => 'textAreaAdjust(this)', 'placeholder' => '內文 . . .']) }}
			</article>				
			{{ Form::hidden('category_id', $teamCategoryId, ['id' => 'category']) }}
			{{ Form::hidden('zone_id', $zone->id) }}
			@if (Input::has('team_id'))
				{{ Form::hidden('team_id', Input::get('team_id')) }}
			@endif
			<div class='article-main-submit center'>
				{{ Form::submit('送出', ['class' => 'btn btn-red']) }}
			</div>
			{{ Form::close() }}	
	</section>

	
@stop