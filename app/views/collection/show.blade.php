@extends('layout')

@section('head')
	@parent
	{{ HTML::script('lib/jquery.ba-bbq.min.js') }}
	{{ HTML::script('js/Pager.js') }}
	
	<script>
		function search(word) {
			if (word == '') {
				$('.suggestNavMain').fadeIn();
				$('.suggestNavTop').slideUp();
				$('#result').empty();
				return;
			}
			goSearch('{{ URL::to("search") }}', {'word' : word});
		}
		
		function goSearch(url, data, isNeedNotPush) {			
			util.ajax(url, data, renderResponse);
			
			isNeedNotPush = isNeedNotPush || false;
			
			if (!isNeedNotPush) {
				var href = pager.makeHref('url', url);
				href = pager.makeHref('data', data, href);
				
				pager.pushState({
					'href' : href,
					'url' : url,
					'data' : data,
				});	
			}			
		}
		
		function setPopEvent() {
			$(window).bind("popstate", function (event){
				var state = event.originalEvent.state;
				if (state != null) {
					goSearch(state.url, state.data);					
					var word = (typeof(state.data) != 'undefined')? state.data.word : '';
					$('.searcher').val(word);
				}
		   	});	
		}
		
		function renderResponse(res) {
			$('.suggestNavMain').fadeOut();
			$('.suggestNavTop').slideDown();
			if (res != '') {
				$('#result').html(res);
				util.setScalableWidth('#result article', 120);
			} else {
				var msg = "<h1 class='title-s textCenter'>Sorry ! 沒有符合的結果</h1>";
				$('#result').html(msg);								
			}
			
		}
				
		function showContent(obj) {
			$(obj).next('article').toggle();	
		}
	
		function triggerSearch(word) {
			$('.searcher').val(word);
			search(word);			
		}
		
		
		function getAllTeams() {
			$('.searcher').val('');
			goSearch('{{ URL::to("search/teams") }}');
		}
		
		function getAllTeamsWithProgress(name) {
			$('.searcher').val('');
			goSearch('{{ URL::to("search/teams/progress") }}', {'name' : name});
		}
		
		util.changeNavFocus(5);
		util.setScalableWidth('#result article', 120);
/* 		util.setScalableWidth('.searcher', 600); */
		setPopEvent();	
		
		$(document).ready(function() {
/*
			$('.suggestNavMain .suggestNav ul li').hover(function() {
				$('.suggestNav-img img').attr('src', 'data/collection/' + $(this).index() + '.jpg');
			});
			$('.suggestNav-img').height($(window).height() - $('.suggestNav-img').offset().top);
*/
			
		});
	</script>
@stop

@section('guide')	

@stop

@section('content')

	<section class='suggestNavTop hidden'>
		<nav class='suggestNav'>
			<ul>
				<li class='btn btn-tran2' onclick="triggerSearch('計劃書')">計劃書</li>
				<li class='btn btn-tran2' onclick="triggerSearch('記錄')">記錄</li>
				<li class='btn btn-tran2' onclick="getAllTeams()">行程</li>
				<li class='btn btn-tran2' onclick="getAllTeamsWithProgress('菜單')">菜單</li>
				<li class='btn btn-tran2' onclick="getAllTeamsWithProgress('包車')">包車</li>
<!-- 				<li class='btn btn-tran2'>舊精華區</li> -->
			</ul>
		</nav>	
	</section>

	<section class='center searcherSection'>
		<h3 class='title'>搜尋出您想找的資料</h3>
		<input class='searcher' onkeyup="util.triggerBtn(event, '.btn-search')" 
			placeholder="輸入關鍵字 (雪山、菜單、審隊、、、)" autocomplete="off">
		<br>
		<button class='btn btn-green btn-search' onclick="search($('.searcher').val())">搜尋</button>		
	</section>

	<section class='suggestNavMain clearfix'>
		<nav class='suggestNav'>
			<ul>
				<li class='btn btn-tran2' onclick="triggerSearch('計劃書')">計劃書</li>
				<li class='btn btn-tran2' onclick="triggerSearch('記錄')">記錄</li>
				<li class='btn btn-tran2' onclick="getAllTeams()">行程</li>
				<li class='btn btn-tran2' onclick="getAllTeamsWithProgress('菜單')">菜單</li>
				<li class='btn btn-tran2' onclick="getAllTeamsWithProgress('包車')">包車</li>
<!-- 				<li class='btn btn-tran2'>舊精華區</li> -->
			</ul>
		</nav>		
		<!--
<div class='suggestNav-img'>
			<img src='data/collection/0.jpg'>
		</div>
-->
	</section>
	<section id='result' class='center'></section>
@stop