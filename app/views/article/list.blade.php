@extends('layout')

@section('head')
	@parent
	{{ HTML::script('lib/jquery.ba-bbq.min.js') }}
	{{ HTML::script('js/Pager.js') }}
	
	
	<script>
		util.changeNavFocus(4);

		util.setScalableWidth('.article-list', 120);


		$(document).ready(function(){
			var articleListPadding = $('.article-list').offset().top;
			pager.page = {{ Input::has("page")? Input::get("page") : 1 }};
			pager.init({{ $articleNum }}, 'ajax/article', '.article-list', '.guide-page' , 73, articleListPadding, '?zone_id={{ $zone->id }}');
			$('footer').hide();
		});
	</script>
@stop

@section('guide')	
	<a href='{{ URL::to("article?zone_id=" . $zone->id) }}'><span class='guide-item guide-main'>{{{ $zone->name }}}</span></a>
	<span class='guide-frame'>
		<a class='btn btn-tran2 guide-item guide-symbol guide-double-symbol' onclick='pager.first()'>
			<span class='lsf'>back</span><span class='lsf'>back</span></a>
		<a class='btn btn-tran2 guide-item lsf guide-symbol' onclick='pager.prev()'>back</a>
		<span class='guide-item guide-desc'>第 <span class='guide-page'></span> 頁</span>
		<a class='btn btn-tran2 guide-item lsf guide-symbol' onclick='pager.next()'>next</a>
		<a class='btn btn-tran2 guide-item guide-symbol guide-double-symbol' onclick='pager.last()'>
			<span class='lsf'>next</span><span class='lsf'>next</span></a>	
	</span>
<!-- 	<a class='btn btn-tran2 guide-item guide-btn' href='{{ URL::to("collection") }}'>搜尋</a> -->
	<a class='btn btn-tran2 guide-item guide-btn' href={{ URL::to('article/create?zone_id=' . $zone->id) }}
		><span class='lsf'>plus</span> 發表</a>	

@stop

@section('content')
	<article class='article-list center'>
	
	</article>

@stop