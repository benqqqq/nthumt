<article class='region-inner'>

	@if (!isset($plan))
		<div class='region-profile'>
			<img src={{ asset($team->profileSrc) }}>
		</div>
	@endif
	
	<h2 class='region-subTitle '>前言</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->foreword)) }}</p>
	
	<h2 class='region-subTitle '>山區簡介</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->intro)) }}</p>
	
	<h2 class='region-subTitle '>日期</h2>
	<p class='region-subTitle-content'><time>{{ $team->startDate . ' ~ ' . $team->backDate }}</time></p>
	
	<h2 class='region-subTitle '>預定行程</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->plan)) }}</p>
	
	
	<h2 class='region-subsubTitle'>- 預定下山時間 : </h2>
	<span class='region-subsubTitle-content'>{{ $team->backDatetime }}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 撤退計畫 : </h2>
	<span class='region-subsubTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->retreat)) }}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 山難期限 : </h2>
	<span class='region-subsubTitle-content'>{{ $team->deadline }}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 留守 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->leftPerson }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 安中 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->safetyPerson }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 交通方式 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->traffic }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 無線電頻道 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->channel }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 台號 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->channelName }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 開機時段 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->channelPeriod }}}</span>
	
	<p>
	
	<h2 class='region-subsubTitle'>- 衛星電話號碼 : </h2>
	<span class='region-subsubTitle-content'>{{{ $team->satellitePhone }}}</span>
	
	
	<h2 class='region-subTitle '>參考資料</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->reference)) }}</p>
					
	<h2 class='region-subTitle '>人員組成</h2>
	<ul class='region-subTitle-content'>
		@if (isset($plan))
			@foreach ($team->members as $member)
				<li class='region-member'>
					<span>{{{ $member->name }}}</span>
					<span>({{{ $member->pivot->teamRole }}})</span>
				</li>
			@endforeach
		@elseif (!Auth::check())
			@foreach ($team->members as $member)
				<li class='region-member'>
					{{ View::make('reuse.user', ['user' => $member]); }} 
					<span>({{{ $member->pivot->teamRole }}})</span>
				</li>
			@endforeach
		@else
			@foreach ($team->members as $member)
				<li class='region-member'>
					{{ View::make('reuse.user', ['user' => $member]); }} 
					<span>({{{ $member->grade }}} {{{ $member->realName }}} {{{ $member->pivot->teamRole }}} )</span>
				</li>
			@endforeach							
		@endif
		{{ Mylib\Util::normal2br(Mylib\Util::purify($team->unregisteredMembers)) }}					
	</ul>
	
	<h2 class='region-subsubTitle'></h2>
	<p class='region-subsubTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->memberComposition)) }}</p>
	
	<h2 class='region-subTitle '>器材裝備</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->equipments)) }}</p>
	
	<h2 class='region-subTitle '>隊員要求</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->memberRequire)) }}</p>
	
	<h2 class='region-subTitle '>隊費</h2>
	<p class='region-subTitle-content'>{{{ $team->fee }}}</p>
	
	<h2 class='region-subTitle '>重要時程</h2>
	<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->importantDate)) }}</p>
	
	<h2 class='region-subTitle '>想說的話</h2>
	<p class='region-subTitle-content'>{{{ $team->greetings }}}</p>
</article>