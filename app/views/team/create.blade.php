@extends('layout')

@section('title')
	新增 -
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
		function makeImagePreview(input) {			
			if (input.files && input.files[0]) {
				reader = new FileReader();
				reader.onload = function (e) {
					$('.region-profile img').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		
		function makeBlockPreview() {
			var article = '';
			article += '<h1>' + $('#name').val() + '</h1>';
			article += '<time>' + $('#startDate').val() + '</time> ~ <time>' + $('#backDate').val() + '</time>';
			article += '<p> ... </p>';
			article += '<p>' + $('#greetings').val() + '</p>';
			$('#preview article').html(article);			
		}
		
		function transfer() {
			var data = $('.pop-importArea').val();
			angular.element(document.querySelector('.smallRegionFrame')).scope().transfer(data);
		}
		
		util.changeNavFocus(3);
				
		userAdder.me = {{ Auth::id() }};
		userAdder.users = {{ $users }};
		userAdder.baseUrl = "{{ URL::to('/') }}";

		
		$(document).ready(function() {
			inputer.addDatepicker(['#startDate', '#backDate']);
			inputer.addDatetimepicker(['#backDatetime', '#deadline']);			
			
			userAdder.initMemberInputEvent();
			userAdder.addLeader({{ Auth::user() }} );
			userAdder.refreshMemberList();
		});
		
	</script>
@stop

@section('guide')	
	<span class='guide-item guide-desc'>新增</span>
	
	<a class='btn btn-tran2 guide-item guide-btn' onclick="popper.importTeam()">匯入社版計劃書</a>
@stop

@section('content')
	<?php
		$defaults = [
			'channel' => '144.51',
			'channelPeriod' => '偶數整點前後5分鐘',
			'memberComposition' => 
'男女比 : 
老新比 : 
有無縱走經驗比 : ',
			'equipments' =>
'◎帳篷
     雪四帳 *
     營釘 *
     營繩 *
     地布 *
     雨布 *

◎炊具
     中鍋 *
     小鍋 *
     擋風板 *
     湯瓢 *
     爐頭 *
     瓦斯 *

◎其他
     大D *
     傘帶10米 *
     傘帶3米 *
     
◎安中器材
     無線電 *
     衛星電話 *
     醫藥包 *
     收音機 *'
		];
	?>
	<div class='smallRegionFrame' ng-controller='createTeamCtrl'>
		<section class='region region-fullPlan'>
			<header class='region-header'>
				<h1 class='region-title yellow'>計劃書</h1>
			</header>
			<article class='region-inner'>
<!-- 				{{ Form::open(['url' => 'team/create', 'method' => 'post', 'id' => 'dataForm']) }} -->
				<form method="post" id="dataForm" ng-submit="submitForm('{{ URL::to('team/create') }}')" >
				
				<div class='region-profile'>
					<div class='imgFrame'>
						<img class='userFrame-profile imgFrame-img' src={{ asset('data/default/team/profile.jpg') }}>
						<span class='btn btn-yellow2 imgFrame-btn' onclick="$('#uploadImage').click()">更換相片</span>
						{{ Form::file('profile', 
							['onchange' => 'popper.uploadImage(this, util.teamProfileCropper);', 'id' => 'uploadImage', 'class' => 'hidden']) }}
					</div>									
				</div>
				{{ Form::hidden('profileDataUrl', null, ['id' => 'profileDataUrl']) }}
									
				<h2 class='region-subTitle '>隊名</h2>
				<p class='region-subTitle-content'>
					{{ Form::text('name', null, ['id' => 'name', 'class' => 'region-fullWidth']) }}
					<span class='red warn' hidden="true">隊名不可空白</span></p>
					
			
				<h2 class='region-subTitle '>前言</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('foreword', null, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>山區簡介</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('intro', null, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>日期</h2>
				<p class='region-subTitle-content'>
					<time>{{ Form::text('startDate', null, ['id' => 'startDate']) . ' ~ ' . 
						Form::text('backDate', null, ['id' => 'backDate']) }}</time>
				</p>
				
				<h2 class='region-subTitle '>預定行程</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('plan', null, ['class' => 'region-fullWidth']) }}</p>
				
				
				<h2 class='region-subsubTitle'>- 撤退計畫 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::textarea('retreat', null, ['class' => 's-height']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 預定下山時間 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('backDatetime', null, ['id' => 'backDatetime']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 山難期限 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('deadline', null, ['id' => 'deadline']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 留守 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('leftPerson', null) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 安中 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('safetyPerson', null) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 交通方式 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('traffic', null) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 無線電頻道 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channel', $defaults['channel']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 台號 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channelName', null) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 開機時段 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('channelPeriod', $defaults['channelPeriod']) }}</span>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 衛星電話號碼 : </h2>
				<span class='region-subsubTitle-content'>{{ Form::text('satellitePhone', null) }}</span>
				
				
				<h2 class='region-subTitle '>參考資料</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('reference', null, ['class' => 'region-fullWidth']) }}</p>
								
				<h2 class='region-subTitle '>人員組成</h2>
				<div class='region-subTitle-content'>	
					{{ View::make('reuse.userAdder') }}
				</div>		
				
				<h2 class='region-subsubTitle '>- 未註冊隊員 :</h2>
				<p class='region-subsubTitle-content'>{{ Form::textarea('unregisteredMembers', null, ['class' => 's-height']) }}</p>
				
				<p>
				
				<h2 class='region-subsubTitle'>- 班底新生比 :</h2>
				<p class='region-subsubTitle-content'>
					{{ Form::textarea('memberComposition', $defaults['memberComposition'], ['class' => 's-height']) }}</p>
				
				<h2 class='region-subTitle '>器材裝備</h2>
				<p class='region-subTitle-content'>
					{{ Form::textarea('equipments', $defaults['equipments'], ['class' => 'region-fullWidth l-height']) }}</p>
				
				<h2 class='region-subTitle '>隊員要求</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('memberRequire', null, ['class' => 'region-fullWidth s-height']) }}</p>
				
				<h2 class='region-subTitle '>隊費</h2>
				<p class='region-subTitle-content'>{{ Form::text('fee', null, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>重要時程</h2>
				<p class='region-subTitle-content'>{{ Form::textarea('importantDate', null, ['class' => 'region-fullWidth s-height']) }}</p>
				
				<h2 class='region-subTitle '>想說的話</h2>
				<p class='region-subTitle-content desc'>(將會呈現在隊伍列表中)</p>
				<p class='region-subTitle-content'>{{ Form::text('greetings', null, ['class' => 'region-fullWidth']) }}</p>
				
				<h2 class='region-subTitle '>其他設定</h2>
				<p class='region-subTitle-content'>
					{{ Form::label('是否為社內隊伍') }}
					{{ Form::checkbox('public', true, true) }}				
				</p>
				<p class='region-subTitle-content'>
					{{ Form::label('是否開放報名(可再行更改)') }}
					{{ Form::checkbox('isEnrolling', true, true) }}
				</p>
				
				<div class='textCenter'>
					<input type=submit class='btn btn-yellow' value='確定'>
				</div>
				{{ Form::close() }}
			</article>
		</section>
	</div>


@stop