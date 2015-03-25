<!doctype html>
<html>
    <head>
    	<meta id='metaImg' property="og:site_name" content="清大山社"/>
		<meta id='metaImg' property="og:type" content="website"/>
<!-- 		<meta id='metaImg' property="og:image" content='{{ asset("img/logo_xl.jpg") }}'/> -->
		<meta id='metaImg' property="og:url" content='{{ URL::to("/") }}'/>
		<meta id='metaImg' property="og:title" content="清大山社"/>
		<meta id='metaImg' property="og:description" content="帶你去離天空更近的地方"/>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<link rel="shortcut icon" href="{{{ asset('img/favicon.png') }}}">
		 
		<title>@yield('title') 清大山社 {{{ $guideTitle }}} </title>
    	
    	@section('head')    
	        {{ HTML::style('css/main.css') }}        
	        {{ HTML::style('lib/LigatureSymbols/ligatureSymbols.css') }}        
	        {{ HTML::script('lib/jquery-1.11.1.min.js') }}
	        {{ HTML::script('js/Popper.js') }}
	        {{ HTML::script('js/Util.js') }}
	        
	        {{ HTML::script('lib/angular-1.2.26.min.js') }}	
	        
	        
			<script type="text/javascript">
				var app = angular.module("mtApp", []);
				
				$(document).ready(function(){
					@if (Session::has('message'))
						popper.showWithTitleMessage('Oops !', "{{ Session::get('message') }}");
					@elseif (Session::has('info'))
						popper.showWithTitleMessage("{{ Session::get('info') }}", '');
					@endif
					
					@if (!isset($noGuide))					
						util.enableGuideMove();
					@endif
					
					util.enableTabInTextarea();
				});
				
				
			</script>
			
<!-- 			Google Analytics -->
			<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			  ga('create', 'UA-39311170-2', 'auto');
			  ga('send', 'pageview');
			
			</script>
		@show
		
    </head>
    <body ng-app='mtApp'>
        <header class='top-header'>
	        	
	        	
	        	<div id='logo' class='leftest'>
	        		<a href={{ URL::to('/') }}>
		        		<img src={{ asset('img/logo.png') }}>
		        		<div class='logo-header'>
			        		<h1>清大山社</h1>
							<h2>帶你去離天空更近的地方</h2>        	
		        		</div>
	        		</a>
	        	</div>
	        	
	        	<nav class='groupHeader groupNav vertical-middle'>
	        		<div class='listFrame btn-green lsf noneSelect' ng-click="groupNavShow = !groupNavShow">list</div>	        		
	        		<ul class='groupNav-nav' ng-show='groupNavShow' ng-init='groupNavShow = false'>
	        			<li><a class='' href={{ URL::to('/') }}>首頁</a></li>
			        	<li><a class='' href={{ URL::to('about') }}>關於山社</a></li>
			        	<li><a class='' href={{ URL::to('team') }}>隊伍</a></li>
			        	<li><a class='' href={{ URL::to('article') }}>討論版</a></li>
			        	<li><a class='' href={{ URL::to('collection') }}>精華區</a></li>
			        	
			        	@if (! Auth::check())			        	
			        		<li><a class='' href={{ URL::to('login') }}>登入</a></li>
			        		<li><a class='' href={{ URL::to('register') }}>註冊</a></li>	        		
			        	@else
			        		<li><a class='' href={{ URL::to('logout') }}>登出</a></li>
			        	@endif
	        		</ul>
	        		
	        		
	        	</nav>
	        	
	        	<nav class='groupHome groupNav vertical-middle'>
	        		<div class='listFrame btn-yellow lsf noneSelect'>home</div>
	        	</nav>
	        	
	        	@if (! Auth::check())
	        	<ul class='inline headerNav rightest' id='btns'>	        		
	        		<li><a class='navBtn leftBorder' href={{ URL::to('login') }}>登入</a></li>
	        		<li><a class='navBtn leftBorder' href={{ URL::to('register') }}>註冊</a></li>	        		
	        	</ul>
	        	@else
	        	<div class='inline rightest' id='profile'>
	        		<a class='navUser' href={{ URL::to("user/" . Auth::id()) }}>
		        		<img src={{ asset(Auth::user()->profileSrc) }}>
		        		<div>
		        			<span>{{{ Auth::user()->name }}}</span>
		        		</div>
	        		</a>
	        		<a class='navBtn leftBorder' href={{ URL::to('logout') }}>登出</a>
	        	</div>
	        	@endif
	        	
	        	<nav class='extendNav'>
		        	<ul class='inline headerNav'>
			        	<li><a class='navBtn navBtn-focus' href={{ URL::to('/') }}>首頁</a></li>
			        	<li><a class='navBtn' href={{ URL::to('about') }}>關於山社</a></li>
			        	<li><a class='navBtn' href={{ URL::to('team') }}>隊伍</a></li>
			        	<li><a class='navBtn' href={{ URL::to('article') }}>討論版</a></li>
			        	<li><a class='navBtn' href={{ URL::to('collection') }}>精華區</a></li>
		        	</ul>	        	
	        	</nav>
	        	
        </header>
		
		<main>
			<section class='guide clearfix'>
				<div class='guide-content vertical-middle'>
					<div class='rectangle leftest'></div>
					<a href='{{ URL::to("$guideLink") }}'><h1>{{{ $guideTitle }}}</h1></a>
					@yield('guide')
				</div>
			</section>
			@yield('content')
		</main>
		
		<footer>
			<span class='desc'>© 2015 NTHU Mountain club</span>
			<br>
			<span class='desc'>任何網站問題、建議請至
				<a class='link' href='{{ URL::to("article?zone_id=" . Zone::devZone()->id) }}'>開發討論版</a> 留言</span>
		</footer>
        
    </body>
</html>