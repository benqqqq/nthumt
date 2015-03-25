<?php
class Category extends Eloquent {

	public $timestamps = false;
	
	public function articles() {
		return $this->hasMany('Article');
	}
	
	public static function teamCategory() {
		return Category::where('name', '隊伍')->first();
	}
	
	public static function planCategory() {
		return Category::where('name', '計劃書')->first();
	}

	public static function recordCategory() {
		return Category::where('name', '記錄')->first();
	}

	public static function creatableCategory() {
		return Category::whereNotIn('name', ['隊伍'])->get();
	}
	
	
	
}

?>