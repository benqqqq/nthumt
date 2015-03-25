<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>認證您的電子信箱</h2>

        <div>
        	感謝您在清華大學登山社創立帳號。<br/>
        	您所建立的帳號暱稱為 {{ $name }}<br/>
        	
        	請點擊以下連結來認證您的電子信箱：
            {{ URL::to('register/verify/' . $confirmationCode) }}.<br/>
            
            (若您未曾建立帳號，請忽略此封郵件)
        </div>
	</body>
</html>
