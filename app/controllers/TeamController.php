<?php

use Illuminate\Support\Collection;

class TeamController extends BaseController {
	
	public function showTeamList() {	
		$teams = Team::where('public', true)->orderBy('startDate', 'desc')->get();		
		return View::make('team.list', ['teams' => $teams, 'public' => true]);	
	}
	
	public function showPrivateTeamList() {	
		$teams = Team::where('public', false)->orderBy('startDate', 'desc')->get();		
		return View::make('team.list', ['teams' => $teams, 'public' => false]);	
	}
		
	public function showTeam($id) {
		$team = Team::with(['members', 'booleanProgresses', 'textProgresses', 'leaders', 'applyers', 'files', 'files.user'])->find($id);
		$this->sortMemberByTeamRole($team);
		
		$articles = $team->articles()->with('user')->orderBy('created_at', 'desc')->get();
		ArticleController::buildArticleRead($articles);
		$isLeader = $this->isLeader($team);
		$isMember = $this->isMember($team);
		
		return View::make('team.show', array('team' => $team, 'articles' => $articles, 'isLeader' => $isLeader, 'isMember' => $isMember));
	}
	
	private function sortMemberByTeamRole($team) {
		$members = [];
		foreach($team->members as $user) {
			array_push($members, $user);
		}
		
		function cmp($a, $b) {
			$role1 = $a->pivot->teamRole;
			$role2 = $b->pivot->teamRole;
			if ($role1 == $role2) {
				return 0;
			} else if (strpos('領隊', $role1) !== false) {
				return -1;
			} else if (strpos('領隊', $role2) !== false) {
				return 1;
			} else if (strpos('嚮導', $role1) !== false) {
				return -1;
			} else if (strpos('嚮導', $role2) !== false) {
				return 1;
			} else if (strpos('實領', $role1) !== false) {
				return -1;
			} else if (strpos('實領', $role2) !== false) {
				return 1;
			} else {
				return strcmp($role1, $role2);
			}			
		}
		usort($members, "cmp");		
		$team->members = $members;		
	}
	
	
	private function isLeader($team) {
		return User::isManager() || ($team->leaders()->find(Auth::id()) !== null);
	}
	
	private function isMember($team) {
		return User::isManager() || ($team->members()->find(Auth::id()) !== null);
	}

	public function showCreateTeam() {		
		if (!Auth::check()) {
			return Redirect::back()->withMessage('請先登入');
		}
	
		$users = User::all();
		return View::make('team.create', array('users' => $users));
	}
	
	public function doCreateTeam() {				
		$teamData = Input::except('_token', 'profile', 'membersInput', 'teamRole', 'members', 'profileDataUrl', 
			'public', 'isEnrolling');
		$teamData['profileSrc'] = 'data/default/team/profile.jpg';
		$teamData['public'] = Input::has('public');
		$teamData['isEnrolling'] = Input::has('isEnrolling');
		
		Log::info('Accept team created', $teamData);
		$team = new Team($teamData);
		DB::transaction(function() use ($team) {					
			$team->createArticle(Auth::id());
			$team->initTeam();
			File::makeDirectory($this->getTeamDirectory($team));
			$this->setPeople($team);			
			$this->setProfile($team);
			$this->setFile($team);
			$team->save();			
			$this->writePlanFile($team);
		});
		$team->updateArticle();
		return Redirect::to('team/' . $team->id);
	}
	
	private function getTeamDirectory($team) {
		return 'data/team/' . $team->id;
	}
	
	private function setPeople($team) {	
		$members = json_decode(Input::only('members')['members']);
		
		if ($members != '') {
			DB::transaction(function() use ($team, $members) {
				$team->members()->detach();
				foreach ($members as $key => $value) {
					$team->members()->attach($key, ['teamRole' => $value->teamRole]);
				}	
			});
		}		
		Log::info('Team created with attach members', [$members]);
	}
	
	private function setProfile($team) {
		$url = Input::get('profileDataUrl');
		$filePath = public_path() . '/' . $this->getTeamDirectory($team) . "/profile.jpg";
		if ($url != null) {
			if (!File::exists($this->getTeamDirectory($team))) {
				File::makeDirectory($this->getTeamDirectory($team));
			}
			Mylib\Util::writeDataUrlToFile($url, $filePath);
			$team->profileSrc = $this->getTeamDirectory($team) . "/profile.jpg";
		}		
	}
	
	private function setFile($team) {
		$fileNames = ['空白人員名冊', '空白出隊紀錄表', '空白器材單'];
		$fileSrc = ['people.docx', 'record.docx', 'equipment.docx'];
		foreach ($fileNames as $i => $fileName) {
			$src = 'default/' . $fileSrc[$i];
			$fileSize = File::size(app_path() . '/storage/data/default/team/' . $fileSrc[$i]);
			TeamFile::create(['name' => $fileName, 'src' => $src, 'public' => true,
				'team_id' => $team->id, 'user_id' => User::manager()->id, 'size' => $fileSize]);
		}
		TeamFile::create(['name' => '計劃書', 'src' => $team->id . '/fullPlan.txt', 'public' => true,
				'team_id' => $team->id, 'user_id' => User::manager()->id]);
	}
	
	private function writePlanFile($team) {		
		$content = $content = View::make('reuse.fullPlanText', ['team' => $team]);
		$dir =  app_path() . '/storage/' . $this->getTeamDirectory($team);					
		if (!File::exists($dir)) {
			File::makeDirectory($dir);
		}
		$filePath = $dir . '/fullPlan.txt';
		$bytes = File::put($filePath, $content);
		
		if ($bytes === false) {
			Redirect::to('/')->withMessage('出了點問題! 麻請請聯絡管理員，謝謝!');
			Log::error('Write plan file to ' . $filePth . ' fail.');
		}
	}
	
	public function deleteTeam($id) {
		$team = Team::find($id);
		if (!$this->isLeader($team)) {
			return Redirect::to('/');
		}
		DB::transaction(function() use ($team) {
			$team->articles()->delete();		
			File::deleteDirectory($this->getTeamDirectory($team));
			File::deleteDirectory(app_path() . '/storage/' . $this->getTeamDirectory($team));
			$team->article->updateIndex();
			$team->article()->delete();
			
			$team->delete();
		});
		Log::info('User ' . Auth::id() . ' delete team ' . $team->id);
		return Redirect::to('team');
	}
	
	public function signUp($id) {
		if (!Auth::check()) {
			return Redirect::to('login');
		}
		$team = Team::find($id);
		$team->applyers()->attach(Auth::id(), ['message' => Input::get('message')]);
		
		return Redirect::to('team');
	}
	
	public function acceptOrReject($teamId, $userId, $isAccept) {
		$team = Team::find($teamId);
		if (!$this->isLeader($team)) {
			return Redirect::back()->withMessage('非領隊不能更動報名者 !');
		}
		
		if ($team->applyers()->find($userId) == null) {
			Log::error('Want to detach user ' . $userId . ' from applyers, but nothing there');
			return Redirect::back()->withMessage('出了點問題 ! 請與管理員聯絡，謝謝 !');
		}
		
		DB::transaction(function() use ($team, $userId, $isAccept) {
			$apply = $team->applyers()->detach($userId);
			$message = new Message();
			if ($isAccept) {			
				$team->members()->attach($userId);
				$message->content = '您已被邀請加入隊伍 : ' . $team->name;
				$team->updateArticle();
			} else {
				$message->content = '隊伍 ' . $team->name . ' 無法將您加入，領隊 : ' . Input::get('reason');
			}
			User::find($userId)->messages()->save($message);
		});
		return Redirect::back();
	}
	
	public function changeBooleanProgress($id) {
		$team = Team::find($id);
		if (!$this->isMember($team)) {
			return Redirect::back()->withMessage('非隊員不能更動進度 !');
		}		
		
		$progress = $team->booleanProgresses()->where('progresses.id', Input::get('progress_id'))->first();		
		$progress->pivot->isComplete = Input::get('checked');
		$progress->pivot->user_id = Auth::id();
		$progress->pivot->save();
				
		return Redirect::back();
	}
	
	public function changeTextProgress($id) {
		$team = Team::find($id);
		if (!$this->isMember($team)) {
			return Redirect::back()->withMessage('非隊員不能更動進度 !');
		}
		
		$progress = $team->textProgresses()->where('progresses.id', Input::get('progress_id'))->first();
		$progress->pivot->content = Input::get('content');
		$progress->pivot->user_id = Auth::id();
		$progress->pivot->save();
		
		return Redirect::back();
	}
	
	public function showFullPlan($id) {
		$team = Team::with('members')->find($id);
		$this->sortMemberByTeamRole($team);
		$isLeader = $this->isLeader($team);
		return View::make('team.fullPlan', ['team' => $team, 'isLeader' => $isLeader]);
	}
	
	public function showEdit($id) {
		$team = Team::with('members')->find($id);
		if (!$this->isLeader($team)) {
			return Redirect::back()->withMessage('非領隊不可修改計劃書');
		}
		$users = User::all();
		return View::make('team.edit', ['team' => $team, 'users' => $users]);
	}
	
	public function doEdit($id) {
		$team = Team::find($id);
		if (!$this->isLeader($team)) {
			return Redirect::back()->withMessage('非領隊不可修改計劃書');
		}
		$teamData = Input::except('_token', 'profile', 'membersInput', 'teamRole', 'members', 'profileDataUrl');

		DB::transaction(function() use ($team, $teamData) {
			$team->update($teamData);
			$this->setProfile($team);
			$this->setPeople($team);
			$team->updateArticle();
			$this->writePlanFile($team);
		});
		return Redirect::to('team/' . $team->id . '/fullPlan');	
	}
	
	public function upload($id) {
		$team = Team::find($id);
		$file = Input::file('file');
		if (!$this->isMember($team)) {
			return Redirect::back()->withMessage('非隊員不能上傳檔案 !');
		} else if ($file == null) {
			return Redirect::back()->withMessage('請選擇上傳的檔案 !');
		}		
		
		$public = Input::has('public') ? true : false;			
		
		$extension = '.' . $file->getClientOriginalExtension();
		$name = $this->getBasename($file->getClientOriginalName());
		$dir =  app_path() . '/storage/' . $this->getTeamDirectory($team);			
		if (!File::exists($dir)) {
			File::makeDirectory($dir);
		}						
		$realName = $name . '_' . str_random(5) . $extension;			
		$file->move($dir, $realName);
		$src = $team->id . '/' . $realName;
		$fileSize = File::size($dir . '/' . $realName);
		TeamFile::create(['name' => $name . $extension, 'public' => $public, 'src' => $src, 'team_id' => $team->id, 'user_id' => Auth::id(), 'size' => $fileSize]);
			
		return Redirect::back()->withInfo('成功上傳');		
	}
	private function getBasename($filename){  
	    return preg_replace('/\.[^.]+$/', '', $filename);
	} 
	public function download($id, $file) {
		if ($id == 'default') {
			$realSrc = app_path() . '/storage/data/default/team/' . $file;
			return Response::download($realSrc);	
		}
		
		$src = $id . '/' . $file;
		$teamFile = TeamFile::where('src', $src)->first();
		if ($teamFile != null) {
			$team = Team::find($id);
			if ($teamFile->public || $this->isMember($team) ) {
				$realSrc = $this->getStoragePath() . $src;
				return Response::download($realSrc);	
			}
		}		
		return Redirect::back()->withMessage('權限不足，不可下載 !');
	}
	
	public function delete($id) {
		$teamFile = TeamFile::with('user')->find($id);
		if ($teamFile->isOwner()) {
			$realSrc = $this->getStoragePath() . $teamFile->src;
			File::delete($realSrc);
			$teamFile->delete();				
			return Redirect::back()->withInfo('刪除成功 !');
		}
		return Redirect::back()->withMessage(' 權限不足，刪除失敗 !');
	}
	
	private function getStoragePath() {
		return app_path() . '/storage/data/team/';
	}

	public function changeFilePublic($id) {
		$file = TeamFile::find(Input::get('fileId'));
		if (!$file->isOwner()) {
			return json_encode(['error' => 1, 'message' => '不可更改其他人上傳者之檔案']);
		} else if ($file != null) {
			$file->public = Input::get('public');
			$file->save();
		}
	}
	
	public function openEnroll($id) {
		$team = Team::find($id);
		if (!$this->isLeader($team)) {
			return Redirect::to('/')->withMessage('非領隊不可開放報名');
		}
		$team->isEnrolling = true;
		$team->save();
		return Redirect::back()->withInfo('開放報名成功');
	}

	public function closeEnroll($id) {
		$team = Team::find($id);
		if (!$this->isLeader($team)) {
			return Redirect::to('/')->withMessage('非領隊不可結束報名');
		}
		$team->isEnrolling = false;
		$team->save();
		return Redirect::back()->withInfo('關閉報名成功');
	}	
}
