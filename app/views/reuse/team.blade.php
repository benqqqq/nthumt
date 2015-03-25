<div class='team' onclick="util.link('{{ URL::to("team/" . $team->id) }}')">
	<img src={{ asset($team->profileSrc) }} class='team-profile'>
	<article class='team-desc'>
		<h1 class='team-desc-name title-underline'>{{{ $team->name }}}</h1>
		<time class='team-desc-item'>{{ $team->startDate }} ~ {{ $team->backDate }}</time>					
		
		<div class='team-desc-left'>
			<span class='team-desc-item team-desc-leaders'>
				<span>領隊 : </span>
				@foreach ($team->leaders as $leader)
					{{ View::make('reuse.user', ['user' => $leader]); }}
				@endforeach
			</span>
			
			<span class='team-desc-item team-desc-members'>
				<span>隊員 : </span>
				@foreach ($team->notLeaders as $member)
					{{ View::make('reuse.user', ['user' => $member]); }}
				@endforeach
				<br>
				{{ Mylib\Util::normal2br(Mylib\Util::purify($team->unregisteredMembers)) }}
			</span>
			
			<span class='team-desc-greetings'>
				{{{ $team->greetings }}}
			</span>
		</div>
		
		<div class='team-desc-right'>
			<span class='team-desc-item team-desc-plan'>
				{{ Mylib\Util::normal2br(Mylib\Util::purify($team->plan)) }}
			</span>
			@if (!$team->isRunning())
				<a class='btn btn-green vertical-middle'><span>報名結束</span></a>
			@elseif (!Auth::check())
				<a class='btn btn-green' onclick="util.stop(); popper.showWithTitleMessage('請先登入', '登入會員後可以進行報名')">報名</a>
			@elseif (Auth::user()->isNotMemberOf($team->id) && Auth::user()->isNotAppliedFor($team->id))
				<a class='btn btn-green' onclick="util.stop(); popper.signUp({{ $team->id }}, 
					'{{{ $team->name }}}', '{{{ json_encode(Mylib\Util::normal2br($team->memberRequire)) }}}')">報名</a>
			@endif
		</div>
		
			
	</article>	
	
</div>		
