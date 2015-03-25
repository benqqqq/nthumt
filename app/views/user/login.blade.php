@extends('layout')

@section('head')
	@parent
	<script>
		$(document).ready(function() {
			$('.guide, footer').hide();
			$('body').css('background', '#00c0b2');
		});
	</script>
		
@stop

@section('content')
	<div class='userLoginReg'>
		<section class='userFrame' ng-controller='loginCtrl'>
			{{ Form::open(array('url' => 'login')) }}
				<h1 class='title-l'>登入</h1>
				
				<p>
					{{ $errors->first('email') }}
					{{ $errors->first('password') }}
					{{ $errors->first('credentials') }}			
					
				</p>
		
				<p>
					{{ Form::text('email', Input::old('email'), 
						['placeholder' => '電子信箱', 'class' => 'userFrame-fullSize', 'ng-model' => 'email']) }}
				</p>
		
				<p>
					{{ Form::password('password', ['placeholder' => '密碼', 'class' => 'userFrame-fullSize']) }}
				</p>
				
				<p>{{ Form::submit('確定', ['class' => 'btn btn-yellow2']) }}</p>
				
				<p>
					{{ Form::checkbox('remember') }}
					<span>記住我</span>					
					<span class='desc white'>(公共場所勿勾選，以免帳號遭竊)</span>
				</p>
				
				<p>
					<span class='btn btn-tran2' ng-click='forgetPassword()'>忘記密碼 ? </span>
					
				</p>
				
			{{ Form::close() }}
		</section>
	</div>
	<script>
		app.controller('loginCtrl', function($scope) {
			$scope.forgetPassword = function() {
				var email = $scope.email || '尚未填寫';
				popper.warn('{{ URL::to("user/forgetPassword") }}' + '/' + email, 
					'確認後，系統將會寄出認證信，請至信箱(' + email + ')收信來重置您的密碼');
			};

		});
	</script>
@stop