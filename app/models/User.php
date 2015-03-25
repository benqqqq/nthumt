<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;	

	protected $guarded = array('id', 'remember_token', 'confirmed');
	protected $hidden = array('password', 'remember_token', 'confirmationCode', 'confirmed');

	public function teams() {
		return $this->belongsToMany('Team');
	}
	
	public function applyTeams() {
		return $this->belongsToMany('Team', 'applies_team_user')->withPivot('message')->withTimestamps();
	}
	
	public function articles() {
		return $this->hasMany('Article');
	}
	
	public function comments() {
		return $this->hasManyThrough('Article', 'Comment');
	}
	
	public function isNotMemberOf($teamId) {
		return ($this->teams()->find($teamId) === null);
	}
	
	public function isNotAppliedFor($teamId) {
		return ($this->applyTeams()->find($teamId) === null);
	}
	
	public function messages() {
		return $this->hasMany('Message');
	}
	
	public function readArticles() {
		return $this->belongsToMany('Article');
	}
		
	public static function manager() {
		return User::where('email', 'GM@nthumt')->first();
	}
	
	public static function isManager() {
		return (Auth::id() == User::manager()->id);
	}
}
