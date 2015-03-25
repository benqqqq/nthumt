@extends('layout')

@section('head')
	@parent
	{{ HTML::style('lib/cropper.css') }}        
    {{ HTML::script('lib/cropper.js') }}
    
	<script>
		$(document).ready(function() {
			$('.guide, footer').hide();
			$('body').css('background', '#00c0b2');
		});
	</script>
@stop


@section('content')
	<div class='userEditInfo'>
		<section class='userFrame'>
		
			<h1 class='title-l title-personal'>個人資訊</h1>
					
		
			{{ Form::open(array('url' => 'user/' . $user->id . '/edit', 'method' => 'post')) }}
				<div class='imgFrame'>
					<img class='userFrame-profile imgFrame-img' src={{ asset($user->profileSrc) }}>
					<span class='btn btn-yellow2 imgFrame-btn' onclick="$('#uploadImage').click()">更換相片</span>
					{{ Form::file('profile', 
						['onchange' => 'popper.uploadImage(this, util.userProfileCropper);', 'id' => 'uploadImage', 'class' => 'hidden']) }}
				</div>				
					{{ Form::hidden('profileDataUrl', null, ['id' => 'profileDataUrl']) }}
					
				<p>
					{{ $errors->first('name') }}
					{{ $errors->first('password') }}
					{{ $errors->first('password_confirmation') }}
				</p>
				
				<p>
					<table>
						<tr>
							<td>暱稱</td><td>{{ Form::text('name', $user->name) }}</td>
						</tr>
						<tr>
							<td>電子信箱</td><td>{{ $user->email }}</td>
						</tr>
						<tr>
							<td>密碼</td>
							<td>
								<span class='btn btn-tran2' ng-click='showPassword=!showPassword;' ng-show='!showPassword'
									><span class='lsf'>edit</span> 更改密碼</span>
								{{ Form::password('password', ['ng-show' => 'showPassword', 'ng-init' => 'showPassword=false']) }}
							</td>
						</tr>
						<tr ng-show='showPassword'>
							<td>密碼確認</td>
							<td>
								{{ Form::password('password_confirmation') }}
							</td>
						</tr>
						
					@if (Auth::check())
						<tr>
							<td>真實姓名</td><td>{{ Form::text('realName', $user->realName) }}</td>
						</tr>
						<tr>
							<td>系級</td><td>{{ Form::text('grade', $user->grade) }}</td>
						</tr>
					@endif
						<tr>
							<td>文章數量</td><td>{{ $user->articles->count() }}</td>
						</tr>
						<tr>
							<td>隊伍數量</td><td>{{ $user->teams->count() }}</td>
						</tr>
						<tr>
							<td>上站次數</td><td></td>
						</tr>
						<tr>
							<td>註冊日期</td><td>{{ $user->created_at }}</td>
						</tr>
						<tr>
							<td>上次登入</td><td></td>
						</tr>
					</table>
				</p>		
				<span class='btn btn-yellow2' onclick="$(this).parents('form').submit()">確定</span>
				<span class='btn btn-yellow2' onclick="document.location='{{ URL::to("user/" . $user->id) }}'">取消</span>
			{{ Form::close() }}
			<p class='desc'>* 真實姓名及系級僅顯示給已註冊會員，請盡量填寫以利隊伍名單完整，方便審隊</p>
		</section>
	</div>
@stop