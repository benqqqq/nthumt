<?php
class Comment extends Eloquent {

	protected $fillable = ['content'];
	
	public function user() {
		return $this->belongsTo('User');
	}	
	
	public function article() {
		return $this->belongsTo('Article');
	}
	
}

?>