<?php

class UserController extends BaseController {

	
	public function showLogin() {
		return View::make('user.login');
	}
	
	public function doLogin() {
		$rules = [
            'email' => 'required|exists:users',
            'password' => 'required'
        ];

        $input = Input::only('email', 'password');

		$messages = [
			'required' => ':attribute 為必填',
			'exists' => '電子信箱名稱或密碼錯誤，(或請至信箱進行認證)',
		];

        $validator = Validator::make($input, $rules, $messages);
		$validator->setAttributeNames(['password' => '密碼', 'email' => '電子信箱']);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $credentials = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'confirmed' => 1
        ];
		
        if (!Auth::attempt($credentials, Input::has('remember'))) {
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'credentials' => '電子信箱名稱或密碼錯誤，(或請至信箱進行認證)',
                ]);
        }                
        
        return Redirect::to('/');
	}
	
	public function doLogout() {
		Auth::logout();
		return Redirect::to('/');
	}
	
	public function showRegister() {
		return View::make('user.register');
	}
	
	public function doRegister() {
	    $rules = [
	        'name' => 'required|min:2',
	        'email' => 'required|email|unique:users',
	        'password' => 'required|confirmed|min:6'
	    ];
	
	    $input = Input::only(
	        'name',
	        'email',
	        'password',
	        'password_confirmation'
	    );
		
		$messages = [
			'min' => ':attribute 必須大於 :min 個字元',
			'required' => ':attribute 為必填',
			'unique' => ':attribute 已被使用',
			'confirmed' => ':attribute 必須一致',
			'email' => ':attribute 不符合格式'
		];
		
	    $validator = Validator::make($input, $rules, $messages);
	
		$validator->setAttributeNames(['name' => '暱稱', 'password' => '密碼', 'email' => '電子信箱']);
	
	    if ($validator->fails()) {
	        return Redirect::back()->withInput()->withErrors($validator);
	    }
	
	    $confirmationCode = str_random(30);
	
	    User::create([
	        'name' => Input::get('name'),
	        'email' => Input::get('email'),
	        'password' => Hash::make(Input::get('password')),
	        'confirmationCode' => $confirmationCode,
	        'profileSrc' => 'data/default/user/profile.jpg',
	    ]);
	
	    Mail::send('emails.auth.verify', ['name' => Input::get('name'), 'confirmationCode' => $confirmationCode]
	    , function($message) {
	        $message->to(Input::get('email'), Input::get('name'))
	            ->subject('認證您的電子信箱');
	    });
	    
	    Log::info('Successful register user ' . Input::get('name') . '(' . Input::get('email') . ')');
		return Redirect::to('login')->withInfo('謝謝您註冊，請至信箱進行認證後登入。');
	}
	
	private function getUserDirectory($user) {
		return 'data/user/' . $user->id;
	}
	
	public function confirm($confirmationCode) {
		if (!$confirmationCode) {
			throw new InvalidConfirmationCodeException;
        }
        
        $user = User::where('confirmationCode', $confirmationCode)->first();
        if (!$user) {
			throw new InvalidConfirmationCodeException();			
        }
                
        $user->confirmed = 1;
        $user->confirmationCode = null;
        $user->save();

		Log::info('Confirm user' . $user->id . '(' . $user->email . ') successful.');
		return Redirect::to('login')->withInfo('您已經成功認證您的帳號！');
	}
	
	public function showUser($userId) {
		$user = User::with(['articles', 'messages', 'teams' => function($q) {
			$q->orderBy('startDate', 'desc');
		}])->find($userId);
		return View::make('user.show', ['user' => $user, 'noGuide' => true]);
	}
	
	public function showEditUser($userId) {
		if (Auth::id() != $userId) {
			return Redirect::to('/');
		}	
		$user = User::find($userId);
		return View::make('user.edit', ['user' => $user, 'noGuide' => true]);
	}
	
	public function doEditUser($userId) {
		if (Auth::id() != $userId) {
			return Redirect::to('/');
		}
		
		$rules = [
	        'name' => 'required|min:2',
	        'password' => 'confirmed|min:6'
	    ];
	    
	    if (Input::has('password')) {
			$input = Input::only(
		        'name',
		        'password',
		        'password_confirmation'
		    );	
		} else {
			$input = Input::only('name');			
		}	    
	    		
		$messages = [
			'min' => ':attribute 必須大於 :min 個字元',
			'required' => ':attribute 為必填',
			'confirmed' => ':attribute 必須一致',
		];
		
	    $validator = Validator::make($input, $rules, $messages);	
		$validator->setAttributeNames(['name' => '暱稱', 'password' => '密碼']);
	
	    if ($validator->fails()) {
	        return Redirect::back()->withInput()->withErrors($validator);
	    }
	    	    		
		$user = User::find($userId);
		$user->name = Input::get('name');
		$user->realName = Input::get('realName');
		$user->grade = Input::get('grade');
		if (Input::has('password')) {
			$user->password = Hash::make(Input::get('password'));
		}
		$this->setProfile($user);
		$user->save();
		return Redirect::to('user/' . $userId);
	}
	
	private function setProfile($user) {
		$url = Input::get('profileDataUrl');
		$filePath = public_path() . '/' .  $this->getUserDirectory($user) . "/profile.jpg";
		if ($url != null) {
			if (!File::exists($this->getUserDirectory($user))) {
				File::makeDirectory($this->getUserDirectory($user));
			}
			Mylib\Util::writeDataUrlToFile($url, $filePath);
			$user->profileSrc = $this->getUserDirectory($user) . "/profile.jpg";
		}		
	}
	
	public function forgetPassword($email) {
		$rules = [
            'email' => 'email',
        ];
		$messages = [
			'email' => ':attribute 不符合格式'
		];
        $validator = Validator::make(['email' => $email], $rules, $messages);
		$validator->setAttributeNames(['email' => '電子信箱']);

        if ($validator->fails()) {
            return Redirect::back()->withMessage($validator->messages()->first('email'));
        }

		$user = User::where('email', $email)->first();
		if (!$user) {
			return Redirect::back()->withmessage('使用者尚未註冊，請確認信箱是否輸入正確');
		}
		
		$confirmationCode = str_random(30);		
		
		$user->confirmationCode = $confirmationCode;
		$user->save();
		
		Mail::send('emails.auth.reminder', ['confirmationCode' => $confirmationCode, 'password' => substr($confirmationCode, -6)]
	    	, function($message) use ($email) {
	        	$message->to($email)->subject('重置密碼');
	    });				
		
		return Redirect::back()->withInfo('成功寄出信件');
	}
	
	public function resetPassword($confirmationCode) {
		$user = User::where('confirmationCode', $confirmationCode)->first();		
		if (!$user) {
			return Redirect::to('/');
		}
		$user->password = Hash::make(substr($confirmationCode, -6));
		$user->save();
		return Redirect::to('/')->withMessage('已成功重置您的密碼');
	}
}
