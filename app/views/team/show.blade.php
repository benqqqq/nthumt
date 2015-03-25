@extends('layout')

@section('title')
	{{{ $team->name }}} -
@stop

@section('head')
	@parent
	{{ HTML::script('js/angular/teamFileCtrl.js') }}
		
	<script>
		function changeBooleanProgress(input) {
			$('.booleanProgress form #progress_id').val(input.value);
			$('.booleanProgress form  #checked').val(input.checked ? 1 : 0);
			$('.booleanProgress form').submit();
			
		}
		
		function showChangeTextProgress(id) {
			$('[class*=input]').hide();
			$('[class*=content]').show();
			
			$('.input' + id).show();
			$('.content' + id).hide();
		}
		
		function changeTextProgress(id) {
			$('.textProgress form #progress_id').val(id);
			$('.textProgress form #content').val($('#content' + id).val());
			$('.textProgress form').submit();
		}
		
		function triggerUpload() {
			$('.upload').fadeIn();
			
		}
		
		function changeFilePublic(input) {
			util.ajax('{{{ URL::to("team/" . $team->id . "/changeFilePublic") }}}', {
				'fileId' : input.value,
				'public' : input.checked ? 1 : 0
			}, function(data) {				
				var data = JSON.parse(data);
				if (data.error == 1) {
					popper.showWithTitleMessage('Oops !', data.message);
				}
			}, 'post');

		}
		
		util.changeNavFocus(3);
		util.setScalableWidth('.regionFrame', 120);

	</script>
	
@stop

@section('guide')	
	
	<a href='{{ URL::to("team/" . $team->id) }}'><span class='guide-item guide-main'>{{{ $team->name }}}</span></a>
	@if ($isLeader)
		<a class='btn btn-tran2 guide-item guide-btn' onclick="popper.warn('{{ URL::to("team/" . $team->id . "/delete") }}', 
			'確定刪除 ? 此動作無法復原')">刪除隊伍</a>
		<a class='btn btn-tran2 guide-item guide-btn' href={{{ URL::to("team/$team->id/edit") }}}>修改計劃書</a>
		@if ($team->isRunning())
			<a class='btn btn-tran2 guide-item guide-btn' href={{{ URL::to("team/$team->id/closeEnroll") }}}>關閉報名</a>
		@else
			<a class='btn btn-tran2 guide-item guide-btn' href={{{ URL::to("team/$team->id/openEnroll") }}}>開放報名</a>	
		@endif
	@endif
	
	@if (!$isLeader && !$team->isRunning())
		<a class='btn btn-tran2 guide-item guide-btn'><span>報名結束</span></a>
	@elseif (!Auth::check())
		<a class='btn btn-tran2 guide-item guide-btn' 
			onclick="util.stop(); popper.showWithTitleMessage('請先登入', '登入會員後可以進行報名')">報名</a>
	@elseif (Auth::user()->isNotMemberOf($team->id) && Auth::user()->isNotAppliedFor($team->id))
		<a class='btn btn-tran2 guide-item guide-btn' 
			onclick="util.stop(); popper.signUp({{ $team->id }}, '{{ $team->name }}', '{{ $team->memberRequire }}')">報名</a>
	@endif
@stop

@section('content')
	<div class='regionFrame'>
		@if ($isLeader)			
			@if ($team->applyers->count() != 0)
				<section class='region region-signUp'>	
					<header class='region-header'>
						<h1 class='region-title gray'>報名名單</h1>
					</header>
					<ul class='region-inner'>
					@foreach ($team->applyers as $applyer)
						<li class='applyer clearfix'>
							{{ View::make('reuse.user', ['user' => $applyer]); }}
	
							{{ Form::open(['url' => 'team/' . $team->id . '/signUp/' . $applyer->id . '/1', 'method' => 'post']) }}
								{{ Form::submit('允許', ['class' => 'btn btn-green']) }}
							{{ Form::close() }}
							<span class='green'> / </span>
							{{ Form::open(['url' => 'team/' . $team->id . '/signUp/' . $applyer->id . '/0', 'method' => 'post']) }}	
								{{ Form::text('reason', null, ['id' => 'reason', 'placeholder' => '寫個原因']) }}
								{{ Form::submit('婉拒', ['class' => 'btn btn-green']) }}
							{{ Form::close() }}						
						</li>
						<p class='applyer-message'>{{ Mylib\Util::normal2br(Mylib\Util::purify($applyer->pivot->message)) }}</p>
					@endforeach
					</ul>
				</section>
			@endif
		@endif
		<section class='region region-plan'>
			<header class='region-header'>
				<h1 class='region-title gray'>計劃書</h1>
				<a class='btn btn-tran' href={{ URL::to("team/$team->id/fullPlan") }}>完整計劃書</a>		
				<span class='desc'>最後修改 : {{ (new Date($team->updated_at))->ago() }}</span>
			</header>
			<article class='region-inner'>
				<h2 class='region-subTitle '>重要時程</h2>
				<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->importantDate)) }}</p>
				<h2 class='region-subTitle'>前言</h2>
				<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->foreword)) }}</p>
				
				<h2 class='region-subTitle'>時間</h2>
				<p class='region-subTitle-content'>{{ $team->startDate }} ~ {{ $team->backDate }}</p>
				
				<h2 class='region-subTitle'>預定行程</h2>
				<p class='region-subTitle-content'>{{ Mylib\Util::normal2br(Mylib\Util::purify($team->plan)) }}</p>
				<h2 class='region-subTitle'>人員組成</h2>
				<ul class='region-subTitle-content'>
					@if (Auth::check())
						@foreach ($team->members as $member)
							<li class='region-member'>
								{{ View::make('reuse.user', ['user' => $member]); }} 
								<span>({{ $member->grade }} {{ $member->realName }} {{ $member->pivot->teamRole }} )</span>
							</li>
						@endforeach				
					@else
						@foreach ($team->members as $member)
							<li class='region-member'>
								{{ View::make('reuse.user', ['user' => $member]); }} 
								<span>({{ $member->pivot->teamRole }})</span>
							</li>
						@endforeach
					@endif
					{{ Mylib\Util::normal2br(Mylib\Util::purify($team->unregisteredMembers)) }}
				</ul>
				
			</article>
		</section>
		
		<section class='region region-progress'>
			<header class='region-header'>
				<h1 class='region-title red'>工作進度</h1>
			</header>
			<article class='region-inner'>
				<section class='booleanProgress'>
					{{ Form::open(['url' => 'team/' . $team->id . '/booleanProgress', 'method' => 'post']) }}
						@foreach ($team->booleanProgresses as $progress)
								<h2 class='region-subTitle'>{{ $progress->name }}</h2>
								
								{{ Form::checkbox("$progress->id", "$progress->id", $progress->pivot->isComplete, 
									['onchange' => "changeBooleanProgress(this)"]) }}							
								
								@if (!is_null($progress->pivotProgressUser))
									<span class='desc'>
										{{ $progress->pivotProgressUpdatedAt }} 由 {{ $progress->pivotProgressUser->name }} 修改
									</span>
								@endif
							<p>
						@endforeach				
						{{ Form::hidden('progress_id', null, ['id' => 'progress_id']) }}
						{{ Form::hidden('checked', null, ['id' => 'checked']) }}
					{{ Form::close() }}
				</section>
				
				<section class='textProgress'>
					{{ Form::open(['url' => 'team/' . $team->id . '/textProgress', 'method' => 'post']) }}
						@foreach ($team->textProgresses as $progress)
							<h2 class='region-subTitle'>{{ $progress->name }}</h2>							
							
							<span class='btn btn-tran content{{ $progress->id }}' 
								onclick='showChangeTextProgress({{ $progress->id }})'>修改</span>
							<span class='btn btn-tran hidden input{{ $progress->id }}' 
								onclick='changeTextProgress({{ $progress->id }})'>送出</span>
							
							@if (!is_null($progress->pivotProgressUser))
								<span class='desc'>
									{{ $progress->pivotProgressUpdatedAt }} 由 {{ $progress->pivotProgressUser->name }} 修改
								</span>
							@endif
												
							<p class='region-subTitle-content'>						
								{{ Form::textarea('content' . $progress->id, $progress->pivot->content, 
									['class' => 'input' . $progress->id . ' hidden', 'id' => 'content' . $progress->id]) }}
								<span class='{{ "content" . $progress->id }}'
									>{{ Mylib\Util::normal2br(Mylib\Util::purify($progress->pivot->content)) }}</span>
							</p>						
						@endforeach
						{{ Form::hidden('progress_id', null, ['id' => 'progress_id']) }}
						{{ Form::hidden('content', null, ['id' => 'content']) }}
					{{ Form::close() }}
				</section>
			</article>
		</section>
		
		<section class='region region-file' ng-controller="teamFileCtrl">
			<header class='region-header'>
				<h1 class='region-title gray'>檔案下載</h1>
				@if ($isMember)
					<button class='btn btn-tran' ng-click="deleteFile('{{{ URL::to('team/delete') }}}')">刪除</button>
					<button class='btn btn-tran' ng-click='showUpload = !showUpload'>上傳</button>
				@endif
			</header>
			<article class='region-inner clearfix'>
				<div class='region-file-upload' ng-show='showUpload'>					
					{{ Form::open(['url' => 'team/' . $team->id . '/upload', 'method' => 'post', 'files' => true]) }}					
						{{ Form::file('file') }}
						{{ Form::label('public', '公開給非隊員 ') }}					
						{{ Form::checkbox('public', 'public', false) }}
						<span class='desc'>(人員名冊勿勾選)</span>
						{{ Form::submit('確定', ['class' => 'btn btn-green']) }}					
					{{ Form::close() }}
				</div>
				<table class='region-file-table'>
					<tr>
						<th>名稱</th><th>大小</th><th>上傳者</th><th>上傳時間</th><th>公開</th>
					</tr>
					@foreach($team->files as $file)
						@if ($file->public || $team->isMember())
							<tr ng-click='select({{{ $file->id }}})' ng-class=trClass[{{{ $file->id }}}]>
								<td><a href='{{{ URL::to("readTeamFile/" . $file->src) }}}'>{{{ $file->name }}}</a></td>
								<td>{{{ MyLib\Util::humanFilesize($file->size, 0) }}}</td>
								<td>{{{ $file->user->name }}}</td><td>{{{ (new Date($file->created_at))->ago() }}}</td>
								<td>
									@if(Auth::id() == $file->isOwner())
										{{ Form::checkbox($file->id, $file->id, $file->public, ['onchange' => "changeFilePublic(this)"]) }}
									@else
										{{ Form::checkbox('readonlyCheckbox', 'readonlyCheckbox', $file->public, 
											['onclick' => 'return false']) }}
									@endif
								</td>
							</tr>
						@endif
					@endforeach
				</table>

			</article>
		</section>
		
		<section class='region-article'>
			<header>
				<h1 class='title-m yellow'>隊伍討論區</h1>
								
				<div class='textCenter'>
					<span class='desc'>+ 行後、記錄請發表於<a class='link'href='{{ URL::to("article") }}'>公開討論版</a></span>
					<a class='btn btn-red' href={{ URL::to('article/create/?team_id=' . $team->id) }}>發表</a>
				</div>
			</header>
			@foreach ($articles as $article)
				{{ View::make('reuse.media.article', ['media' => $article]) }}
			@endforeach
			
		</section>
	</div>
@stop