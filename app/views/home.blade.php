@extends('layout')

@section('head')
	@parent
	<script>
	
		var curPreviewNum = -1;
		util.setScalableWidth('#newArticle', 120);		
		util.setScalableWidth('.media-comment', 137);		
	
		function adjustPreview() {
			var windowWidth = $(window).width();
			var previewNum = Math.ceil(windowWidth / $('.preview-item-profile').width());
			if (previewNum != Infinity && previewNum != curPreviewNum) {			
				$('.preview-item').hide();
				for (var i = 0; i < previewNum; ++i) {
					$('.preview-item-' + i).fadeIn();
				}				
				curPreviewNum = previewNum;
			}
			var previewItemMargin = parseInt($('.preview-item').css('margin-right')) + parseInt($('.preview-item').css('margin-left'));
			var previewItemWidth = (1 / curPreviewNum) * windowWidth - previewItemMargin;
			$('.preview-item').css('width', previewItemWidth);
		}
	
		$(window).resize(function() {			
			adjustPreview();
		});
	
		$(document).ready(function() {
			$('.guide').hide();
			adjustPreview();
			
		});
	</script>
@stop


@section('content')
	<section class='center middleWidth' id='newActivity'>
		<div class='center clearfix' id='preview'>
			<?php $i = 0 ?>
			@foreach ($teams as $team)
				<div class='preview-item preview-item-{{ $i++ }}'>
					<div class='shadowEffect clearfix'>
						<a class='clearfix' href={{ URL::to('team/' . $team->id) }}>
							<img class='preview-item-profile' src={{  asset($team->profileSrc) }}>						
						</a>
						<span class='preview-item-hidden'>
							<a class='btn btn-white' href={{ URL::to('team/' . $team->id) }}>了解更多</a><br>
							
							@if ($team->isRunning())
								@if (!Auth::check())
									<a class='btn btn-white' onclick="
										popper.showWithTitleMessage('請先登入', '登入會員後可以進行報名')">報名</a>
								@elseif (Auth::user()->isNotMemberOf($team->id) && Auth::user()->isNotAppliedFor($team->id))
									<a class='btn btn-white' 
										onclick="popper.signUp({{ $team->id }}, 
										'{{{ $team->name }}}', '{{{ json_encode(Mylib\Util::normal2br($team->memberRequire)) }}}')">報名</a>
								@endif							
							@endif
						</span>
					</div>
					
					<span class='preview-item-title'>
						<a class='link' href={{ URL::to('team/' . $team->id) }}>
							<span>{{ $team->name }}</span>
							<time>{{ $team->startDate }} ~ {{ $team->backDate }}</time>
						</a>
					</span>
				</div>
			@endforeach
		</div>		
		
	</section>
	
	<section id='welcome' class='clearfix'>
		<h1 class='leftest'>如何加入清大山社 ?</h1>
		<h2>密切注意新隊伍、社課，或是每週二風雲樓聚餐 歡迎加入 !</h2>
				
		<a class='btn btn-red' href='article'>更多討論</a>
		<a class='btn btn-yellow2' href={{ URL::to('team') }}>更多隊伍</a>
		<a class='btn btn-white' href={{ URL::to('about') }}>了解山社</a>
	</section>
	
	<section id='newArticle'>
		@foreach ($articles as $article)
			{{ View::make('reuse.media.article', ['media' => $article]) }}
			@foreach ($article->comments as $comment)
				{{ View::make('reuse.media.comment', ['media' => $comment, 'class' => 'rightest-comment']) }}
			@endforeach
		@endforeach
		
		<p>
	</section>
	
	
@stop