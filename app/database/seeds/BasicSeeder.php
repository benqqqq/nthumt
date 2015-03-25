<?php

class BasicSeeder extends Seeder {
	public function run() {
		
		$this->removeOldData();
		$this->createGM();
		$this->createDefaultProgresses();
		$this->createDefaultCategories();
		$this->createDefaultZones();
	}
	
	private function removeOldData() {
		Log::info('Clean team data : ' . File::cleanDirectory(public_path() . '/data/team'));
		Log::info('Clean user data : ' . File::cleanDirectory(public_path() . '/data/user'));
		
		DB::table('zones')->delete();	
		DB::table('teams')->delete();		
		DB::table('comments')->delete();
		DB::table('articles')->delete();
		DB::table('categories')->delete();
		DB::table('users')->delete();
		DB::table('progresses')->delete();
	}
	
	private function createGM() {
		return User::create(['email' => 'GM@nthumt', 'name' => '管理員', 'password' => Hash::make('pusheen'), 'confirmed' => true,
			'profileSrc' => 'data/default/user/profile.jpg']);
	}
	
	private function createDefaultProgresses() {
		$progresses = array(
			array('name' => '保險', 'isBoolean' => true), array('name' => '校內公文', 'isBoolean' => true), 
			array('name' => '入山證', 'isBoolean' => true), array('name' => '入園證', 'isBoolean' => true),
			array('name' => '包車', 'isBoolean' => false), array('name' => '菜單', 'isBoolean' => false)		
		);
		DB::table('progresses')->insert($progresses);
		
	}
	
	private function createDefaultCategories() {		
		$categories = array(
			['name' => '隊伍', 'content' => ''], ['name' => '計劃書', 'content' => ''],
			['name' => '公告', 'content' => ''], ['name' => '行後', 'content' => 
'1. 出隊前的準備

2. 出發前的計畫與實際登山行程的出入

3. 意外狀況

4. 隊員於山上的表現與身體狀況

5. 隊伍檢討

6. 器材使用狀況

7. 安中器材使用狀況

8. 安中基金是否繳交

9. 器材費是否繳交

10.文資

11.本隊事蹟與重大發現
'], 
			['name' => '留守', 'content' => ''], ['name' => '安中', 'content' => ''], 
			['name' => '討論', 'content' => ''], ['name' => '其他', 'content' => ''],
			['name' => '記錄', 'content' => ''],
		);
		DB::table('categories')->insert($categories);				
	}
	
	
	private function createDefaultZones() {
		$zones = array(
			['name' => '隊伍'],
			['name' => '公開討論版'],			
			['name' => '開發'],
		);
		DB::table('zones')->insert($zones);			
	}
	
}