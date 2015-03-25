<!doctype html>
<html>
    <head>
        {{ HTML::style('css/main.css') }}        
        {{ HTML::script('lib/jquery-1.11.1.min.js') }}
        {{ HTML::script('js/Popper.js') }}
        {{ HTML::script('js/Util.js') }}
        
        <script>
        	var callback = function(res) {
				popper.showWithTitleMessage('Response', res);
			}
        </script>
        
    </head>
</html>

<body>	
	<btn class='btn btn-red' onclick="util.ajax('deploy', null ,callback)">deploy</btn>
	<btn class='btn btn-green' onclick="util.ajax('db/refresh', null ,callback)">DB Refresh</btn>
	<btn class='btn btn-green' onclick="util.ajax('db/reset', null ,callback)">DB Reset</btn>
	<btn class='btn btn-green' onclick="util.ajax('db/migrate', null ,callback)">DB Migrate</btn>
	<btn class='btn btn-green' onclick="util.ajax('db/seed', null ,callback)">DB Seed</btn>
</body>