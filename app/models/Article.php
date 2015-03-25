<?php
class Article extends Eloquent {
	
	protected $fillable = ['index', 'title', 'content', 'category_id', 'team_id', 'zone_id', 'user_id'];
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function category() {
		return $this->belongsTo('Category');
	}
	
	public function zone() {
		return $this->belongsTo('Zone');
	}
	
	public function comments() {
		return $this->hasMany('Comment');
	}
	
	public function team() {
		return $this->belongsTo('Team');
	}
	
	public function fullPlanTeam() {
		return $this->hasOne('Team');
	}
	
	public function hasTeam() {
		return count($this->fullPlanTeam) != 0;
	}
	
	public function commentNum() {
		return $this->comments()->count();
	}
	
	public function readUsers() {
		return $this->belongsToMany('User', 'article_user')->withPivot('type');
	}
	
	public function isAuthor() {
		return User::isManager() || Auth::id() == $this->user->id;
	}
	
	public function setIndex() {
		if ($this->zone_id == Zone::teamZone()->id) {
			$this->index = Article::where('team_id', $this->team_id)->count() + 1;
		} else {
			$this->index = Article::where('zone_id', $this->zone_id)->count() + 1;
		}
		$this->save();
	}		
	public function updateIndex() {
		$deletedIndex = $this->index;
		if ($this->zone_id == Zone::teamZone()->id) {
			Article::where('team_id', $this->team_id)->where('index', '>', $deletedIndex)->decrement('index');
		} else {
			Article::where('zone_id', $this->zone_id)->where('index', '>', $deletedIndex)->decrement('index');
		}
		$this->save();
	}	
	
}

?>