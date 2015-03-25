<div class='media media-article media-teamItem' onclick='showContent(this)'>
	{{ $user }}
	
	
	<span class='media-item media-period rightest'><time>{{ $team->startDate }} <br> ~ {{ $team->backDate }}</time></span>
	
	<a class='media-item media-btn btn btn-tran boxSizing media-goTeam' href={{ URL::to('team/' . $team->id) }}>前往隊伍</a>
	<span class='media-item media-btn btn btn-tran boxSizing media-check'>查看</span>	
	
	<span class='media-item media-title'><span>{{{ $team->name }}}</span></span>
</div>
<article class='media-hidden hidden'>
	<p>
		{{ Mylib\Util::normal2br(Mylib\Util::purify($content)) }}
	</p>
</article>