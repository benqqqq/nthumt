<?php
class TeamFile extends Eloquent {
	
	protected $table = 'teamFiles';
	protected $guarded = array('id', 'created_at', 'updated_at');
	
	public function team() {
		return $this->belongsTo('Team');
	}
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function isOwner() {
		return User::isManager() || (Auth::id() == $this->user->id);
	}
		
}

?>