<?php 
	$commentColor = 'bck-gray';
	if ($media->commentNum() > 9) {
		$commentColor = 'bck-red';
	} else if ($media->commentNum() > 0) {
		$commentColor = 'bck-yellow';
	}
	
	$categoryColor = 'gray';
	switch($media->category->name) {		
		case '計劃書' : case '記錄' : case '隊伍' :
			$categoryColor = 'yellow';
			break;
		case '公告':	case '安中': 
			$categoryColor = 'red';
			break;
		case '留守' : case '行後' :
			$categoryColor = 'green';
			break;
	}
	
	$readColor = 'bck-yellow';
	$titleColor = 'black';
	switch($media->isRead) {
		case '0':
			$readColor = 'bck-white';
			$titleColor = 'gray2';
			break;
		case '1':
			$readColor = 'bck-yellow2';
			$titleColor = 'gray2';
	}
	
?>

<div class='media media-article' onclick="util.link('{{ URL::to("article/" . $media->id) }}')">

	<div class='media-item media-indexFrame'>
		<span class='media-item media-index'>{{ $media->index }}</span>
	</div>
	
	<div class='media-item media-color {{ $readColor }}'></div>
	
	{{ View::make('reuse.user', ['user' => $media->user]) }}	

	<span class='media-item media-categoryName {{ $categoryColor }}'>{{ $media->category->name }}</span>
	
	
	<span class='media-item media-commentNum {{ $commentColor }} rightest'>{{ $media->commentNum() }}</span>		
	<time class='media-item media-time'>{{ $media->created_at }}</time>
	
	<span class='media-item media-title {{ $titleColor }}'><span>{{{ $media->title }}}</span></span>
	
</div>