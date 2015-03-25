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
		<section class='userFrame'>
			{{ Form::open(array('url' => 'register')) }}
				<h1 class='title-l'>註冊</h1>
		
				<p>
					{{ $errors->first('name') }}
					{{ $errors->first('email') }}
					{{ $errors->first('password') }}
					{{ $errors->first('password_confirmation') }}
				</p>
		
				<p>
					{{ Form::text('name', Input::old('name'), ['placeholder' => '暱稱', 'class' => 'userFrame-fullSize']) }}
				</p>
		
				<p>
					{{ Form::text('email', Input::old('email'), ['placeholder' => '電子信箱', 'class' => 'userFrame-fullSize']) }}
				</p>
		
				<p>
					{{ Form::password('password', ['placeholder' => '密碼', 'class' => 'userFrame-fullSize']) }}
				</p>
				
				<p>
					{{ Form::password('password_confirmation', ['placeholder' => '密碼確認', 'class' => 'userFrame-fullSize']) }}
				</p>
		
				<p>{{ Form::submit('確定', ['class' => 'btn btn-yellow2']) }}</p>
				
			{{ Form::close() }}
		</section>
	</div>
@stop