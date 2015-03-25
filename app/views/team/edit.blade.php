@extends('layout')

@section('title')
	{{{ $team->name }}} -
@stop

@section('head')
	@parent
	{{ HTML::style('lib/jquery.datetimepicker.css') }}        
    {{ HTML::script('lib/jquery.datetimepicker.js') }}
    {{ HTML::style('lib/cropper.css') }}        
	{{ HTML::script('lib/cropper.js') }}
	
	{{ HTML::script('js/Inputer.js') }}
	{{ HTML::script('js/UserAdder.js') }}	
	{{ HTML::script('js/Checker.js') }}
		
	{{ HTML::script('js/angular/createTeamCtrl.js') }}
	<script>
		util.changeNavFocus(3);
				
		userAdder.me = {{ Auth::id() }};
		userAdder.users = {{ $users }};
		userAdder.baseUrl = "{{ URL::to('/') }}";
		
		$(document).ready(function() {
			inputer.addDatepicker(['#startDate', '#backDate'], ['{{ $team->startDate }}', '{{ $team->backDate }}']);
			inputer.addDatetimepicker(['#backDatetime', '#deadline'], ['{{ $team->backDatetime }}', '{{ $team->deadline }}']);			
			@foreach ($team->members as $member)
				userAdder.addUser({{ $member }}, '{{ $member->pivot->teamRole }}');
			@endforeach
			
			userAdder.initMemberInputEvent();
			userAdder.refreshMemberList();
		});
	</script>
@stop

@section('guide')	
	<a href='{{ URL::to("team/" . $team->id) }}'><span class='guide-item guide-main'>{{{ $team->name }}}</span></a>
	<span class='guide-item guide-desc'>修改計劃書</span>
@stop

@section('content')
	<div class='smallRegionFrame' ng-controller='createTeamCtrl'>
		<section class='region region-fullPlan'>
			<header class='region-header'>
				<h1 class='region-title yellow'>計劃書</h1>
			</header>
			<article class='region-inner'>
			
				<form method="post" id="dataForm" ng-submit="submitForm('{{ URL::to('team/' . $team->id . '/edit') }}')" enctype="multipart/form-data">
				
				<div class='region-profile'>
					<div class='imgFrame'>
						<img class='userFrame-profile imgFrame-img' src={{ asset($team->profileSrc) }}>
						<span class='btn btn-yellow2 imgFrame-btn' onclick="$('#uploadImage').click()">更換相片</span>
						{{ Form::file('profile', 
							['onchange' => 'popper.uploadImage(this, util.teamProfileCropper);', 'id' => 'uploadImage', 'class' => 'hidden']) }}
					</div>									
				</div>
				{{ Form::hidden('profileDataUrl', null, ['id' => 'profileDataUrl']) }}

				<h2 class='region-subTitle '>隊名</h2>
				<p class='region-subTitle-content'>{{ Form::text('name', $team->name, ['id' => 'name', 'class' => 'region-fullWidth']) }}<span class='red warn' hidden="true">隊名不可空白</span></p>
			
			
				<h2 class='region-subTitle '>前言</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('foreword', $team->foreword, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>山區簡介</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('intro', $team->intro, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>日期</h2>
				<p class='region-subTitle-content'>
					<time>{{ Form::text('startDate', null, ['id' => 'startDate']) . ' ~ ' . 
						Form::text('backDate', null, ['id' => 'backDate']) }}</time>
				</p>
				
				<h2 class='region-subTitle '>預定行程</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('plan', $team->plan, ['class' => 'region-fullWidth']) }}</p>
				
				
				<h2 class='region-subsubTitle'>- 撤退計畫 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::textarea('retreat', $team->retreat) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 預定下山時間 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('backDatetime', null, ['id' => 'backDatetime']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 山難期限 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('deadline', null, ['id' => 'deadline']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 留守 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('leftPerson', $team->leftPerson) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 安中 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('safetyPerson', $team->safetyPerson) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 交通方式 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('traffic', $team->traffic) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 無線電頻道 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channel', $team->channel) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 台號 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channelName', $team->channelName) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 開機時段 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channelPeriod', $team->channelPeriod) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 衛星電話號碼 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('satellitePhone', $team->satellitePhone) }}</span>
				
				
				<h2 class='region-subTitle '>參考資料</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('reference', $team->reference, ['class' => 'region-fullWidth']) }}</p>
								
				<h2 class='region-subTitle '>人員組成</h2>
				<div class='region-subTitle-content'>	
					{{ View::make('reuse.userAdder') }}
				</div>		
				
				<h2 class='region-subsubTitle '>- 未註冊隊員 :</h2>
				<p class='region-subsubTitle-content'>{{ Form::textarea('unregisteredMembers', $team->unregisteredMembers) }}</p>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 班底新生比 :</h2>
				<p class='region-subsubTitle-content'>{{ Form::textarea('memberComposition', $team->memberComposition) }}</p>
				
				<h2 class='region-subTitle '>器材裝備</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('equipments', $team->equipments, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>隊員要求</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('memberRequire', $team->memberRequire, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>隊費</h2>
				<p class='region-subTitle-content'>{{ Form::text('fee', $team->fee, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>重要時程</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('importantDate', $team->importantDate, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>想說的話</h2>
				<p class='region-subTitle-content'>{{ Form::text('greetings', $team->greetings, ['class' => 'region-fullWidth']) }}</p>
				
				<div class='textCenter'>
				{{ Form::submit('確定', ['class' => 'btn btn-yellow']) }}
				</div>
				{{ Form::close() }}
			</article>
		</section>
	</div>


@stop