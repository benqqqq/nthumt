@extends('layout')

@section('head')
	@parent
	
	<script>
	
		function moveImg() {
			var padding = [-120, -400, -580];
			var curPos = $(document).scrollTop();						
			$('.movingImg').each(function(index) {			
				$(this).css('background-position-y', padding[index] -curPos*0.7);	
			});
		}
			
		$(window).scroll(function() {
/* 			moveImg(); */
		});
		
		$(document).ready(function() {
/* 			moveImg(); */
		});
		
		util.changeNavFocus(2);
	</script>

	
@stop

@section('guide')	

@stop

@section('content')

<div class='movingImg' style="background: url('data/about/1.jpg') fixed; background-size: cover">
	<span>清大登山社至2015年已滿 .. 年，最初由 ...</span>
</div>

<?php 
	$user1 = User::find(12);
	$user2 = User::find(13);
	$user3 = User::find(23);
	
?>

<article class='aboutBlock'>
	<div class='aboutBlock-center'>
		<h1 class='green'>現任幹部</h1>
		<ul>
			<li><span class='li-title'>社長 : </span>				
				@if ($user1 != null)
					{{ View::make('reuse.user', ['user' => $user1]) }}
				@endif				
			</li>
			<li><span class='li-title'>副社長 : </span>				
				@if ($user2 != null)
					{{ View::make('reuse.user', ['user' => $user2]) }}
				@endif
			</li>
<!--
			<li>文資 : 陳以萱</li>		
			<li>總務 : 王建評</li>
			<li>器材 : 陳政文</li>
			<li>課程 : 張瑋鑫</li>
-->
		</ul>
	</div>
</article>
<div class='decorationBlock greenBlock'>
	<img src={{ asset('img/clubMark.png') }}>
</div>


<div class='movingImg' style="background: url('data/about/2.jpg') fixed; background-size: cover">
	<span></span>
</div>

<div class='decorationBlock yellowBlock'></div>
<article class='aboutBlock'>
	<div class='aboutBlock-center'>
		<h1 class='yellow'>社窩</h1>
		<ul>
			<li>位置 : 活動中心501</li>
		</ul>
	</div>
</article>

<div class='movingImg' style="background: url('data/about/3.jpg') fixed; background-size: cover">
	<span></span>
</div>
	
<article class='aboutBlock'>
	<div class='aboutBlock-center'>
		<h1 class='red'>安中群</h1>
		<ul>
			<li>
				@if ($user3 != null)
					{{ View::make('reuse.user', ['user' => $user3]) }}
				@endif
			</li>
			<li>詣醇</li>
			<li>
				@if ($user2 != null)
					{{ View::make('reuse.user', ['user' => $user2]) }}
				@endif
			</li>			
		</ul>	
	</div>
</article>
<div class='decorationBlock redBlock'></div>

@stop