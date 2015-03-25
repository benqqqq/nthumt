<?php
class Team extends Eloquent {
	
	const leader = '領隊';
	protected $guarded = array('id', 'created_at', 'updated_at');
	
	public function members() {
		return $this->belongsToMany('User')->withPivot('teamRole');
	}
	
	public function applyers() {
		return $this->belongsToMany('User', 'applies_team_user')->withPivot('message')->withTimestamps();
	}
		
	public function booleanProgresses() {
		return $this->belongsToMany('Progress', 'booleanProgress_team')->withPivot('isComplete', 'user_id')->withTimestamps();
	}	
	
	public function textProgresses() {
		return $this->belongsToMany('Progress', 'textProgress_team')->withPivot('content', 'user_id')->withTimestamps();
	}	

	public function article() {
		return $this->belongsTo('Article');
	}

	public function articles() {
		return $this->hasMany('Article');
	}
	
	public function files() {
		return $this->hasMany('TeamFile');
	}
	
	public function getStartDateAttribute($value) {
		return (new Date($value))->format('Y/m/d');
	}
	
	public function getBackDateAttribute($value) {
		return (new Date($value))->format('Y/m/d');
	}
	
	public function getBackDatetimeAttribute($value) {
		return (new Date($value))->format('Y/m/d H:i');
	}
	
	public function getDeadlineAttribute($value) {
		return (new Date($value))->format('Y/m/d H:i');
	}
	
	public function isRunning() {
		return $this->isEnrolling;
	}
	
	public function initTeamWithPeople($members, $leader) {
		$this->members()->sync($members);
		$this->setLeader($leader);
		$this->initTeam();
	}
	
	public function initTeam() {
		$this->addDefaultProgresses();
	}
		
	private function addDefaultProgresses() {	
		$booleanProgressIds = Progress::where('isBoolean', true)->lists('id');
		$this->booleanProgresses()->attach($booleanProgressIds);
		$textProgressIds = Progress::where('isBoolean', false)->lists('id');
		$this->textProgresses()->attach($textProgressIds, array('content' => ''));
	}	
	
	public function leaders() {
		return $this->members()->where('teamRole', 'like' , '%' . self::leader . '%');
	}
	
	public function setLeader($user_id) {	
		$user = $this->members()->where('user_id', $user_id)->first();
		$user->pivot->teamRole = self::leader;
		$user->pivot->save();
	}
	
	public function notLeaders() {
		return $this->members()->where('teamRole', 'not like', '%' . self::leader . '%');
	}
	
	public function isMember() {
		return User::isManager() || ($this->members()->find(Auth::id()) !== null);
	}
	
	public function createArticle($user_id) {
		$article = new Article(['index' => 0, 'title' => $this->name, 'content' => '', 'user_id' => $user_id,
			'zone_id' => Zone::mainZone()->id, 'category_id' => Category::planCategory()->id]);			
		$article->setIndex();		
		$article->fullPlanTeam()->save($this);
	}
	
	public function updateArticle() {
		$article = $this->article;
		$article->content = View::make('reuse.fullPlan', ['team' => $this, 'plan' => true]);
		$article->title = $this->name;	
		$article->save();
	}
}

?>