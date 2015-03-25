<?php
class Progress extends Eloquent {
	public $timestamps = false;
	
	public function teams() {
		if ($this->isBoolean) {
			return $this->belongsToMany('Team', 'booleanProgress_team')->withPivot('isComplete', 'user_id');	
		} else {
			return $this->belongsToMany('Team', 'textProgress_team')->withPivot('content', 'user_id');
		}	
	}
	
	public function getPivotProgressUserAttribute() {
		if ($this->pivot === null) {			
			return null;
		}
		return User::find($this->pivot->user_id);
	}	
	
	public function getPivotProgressUpdatedAtAttribute() {
		if ($this->pivot === null) {
			return null;
		}
		return (new Date($this->pivot->updated_at))->ago();
	}
}

?>