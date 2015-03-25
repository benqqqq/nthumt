<?php
class Zone extends Eloquent {
	
	public function articles() {
		return $this->hasMany('Article');
	}
	
	public static function mainZone() {
		return Zone::where('name', '公開討論版')->first();
	}
	
	public static function teamZone() {
		return Zone::where('name', '隊伍')->first();
	}
	
	public static function devZone() {
		return Zone::where('name', '開發')->first();
	}
}

?>