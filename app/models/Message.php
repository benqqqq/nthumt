<?php
class Message extends Eloquent {
	
	public function user() {
		return $this->belongsTo('User');
	}
		
}

?>