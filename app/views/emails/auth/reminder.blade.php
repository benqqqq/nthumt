<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>清大山社</h1>
		
		<h2>密碼重置</h2>

		<div>
			點擊以下連結來重置您的密碼為 : {{ $password }}<br/>
			{{ URL::to('user/resetPassword/' . $confirmationCode) }}<br/>
			
			(如果您未曾點擊 "忘記密碼" ，請忽略此封郵件)
		</div>
	</body>
</html>
