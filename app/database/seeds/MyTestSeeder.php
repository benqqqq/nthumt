<?php

class MyTestSeeder extends Seeder {
	public function run() {
		
		
		$users = array(
			array('id' => 1, 'name' => 'Pusheen', 'email' => 'pusheen@gmail.com', 'password' => Hash::make('pusheen'), 
				'profileSrc' => 'data/default/user/profile.jpg', 'confirmed' => true),
			array('id' => 2, 'name' => 'cat', 'email' => 'cat@gmail.com', 'password' => Hash::make('cat'),
				'profileSrc' => 'data/default/user/profile.jpg', 'confirmed' => true),
			array('id' => 3, 'name' => 'dog', 'email' => 'dog@gmail.com', 'password' => Hash::make('dog'),
				'profileSrc' => 'data/default/user/profile.jpg', 'confirmed' => true),
			array('id' => 4, 'name' => 'lion', 'email' => 'lion@gmail.com', 'password' => Hash::make('lion'),
				'profileSrc' => 'data/default/user/profile.jpg', 'confirmed' => true),
		);		
		DB::table('users')->insert($users);

		
		$teams = array(
			new Team(['id' => 1, 'name' => '枕頭山', 'foreword' => '走~~', 'intro' => '枕頭山位於...',
				'plan' => 'Day 0 : 枕頭登山口', 'startDate' => '2014/12/1' ,'backDate' => '2014/12/5', 'backDateTime' => '2014/12/5 16:00',
				'retreat' => '未到枕頭登山口就撤退', 'deadline' => '2014/12/5 21:00',
				'leftPerson' => 'Cat', 'safetyPerson' => 'Pusheen爸', 'traffic' => '自行開車', 
				'channel' => '144.51', 'channelName' => 'PP',
				'channelPeriod' => '偶數整點前後五分鐘', 'satellitePhone' => 'No', 'reference' => '2010 枕頭山',
				'unregisteredMembers' => '某A 某B', 'memberRequire' => '自行練跑', 'memberComposition' => '男女比 : 2:1',
				'fee' => '1500', 'importantDate' => '6/20行前',
				'profileSrc' => 'data/default/team/profile.jpg', 'greetings' => '誠徵班底!!!!', 'equipments' => '雪四 * 1'
			]), 
			new Team(['id' => 2, 'name' => '五寮尖', 'foreword' => '走~~', 'intro' => '五寮尖位於...',
				'plan' => 'Day 0 : 五寮尖登山口', 'startDate' => '2014/6/1', 'backDate' => '2014/6/5', 'backDatetime' => '2014/6/5 16:00', 
				'retreat' => '未到枕頭登山口就撤退', 'deadline' => '2014/7/5 21:00',
				'leftPerson' => 'Cat', 'safetyPerson' => 'Pusheen爸', 'traffic' => '自行開車', 
				'channel' => '144.51', 'channelName' => 'PP',
				'channelPeriod' => '偶數整點前後五分鐘', 'satellitePhone' => 'No', 'reference' => '2010 五寮尖', 
				'unregisteredMembers' => '某A 某B', 'memberRequire' => '自行練跑', 'memberComposition' => '男女比 : 2:1',
				'fee' => '1500', 'importantDate' => '6/20行前',
				'profileSrc' => 'data/default/team/profile.jpg', 'greetings' => '誠徵班底!!!!', 'equipments' => '雪四 * 1'
			]),
			new Team(['id' => 3, 'name' => '陽明山', 'foreword' => '走~~', 'intro' => '五寮尖位於...',
				'plan' => 'Day 0 : 五寮尖登山口', 'startDate' => '2014/11/1', 'backDate' => '2014/11/8', 
				'backDatetime' => '2014/11/8 16:00', 
				'retreat' => '未到枕頭登山口就撤退', 'deadline' => '2014/11/8 21:00',
				'leftPerson' => 'Cat', 'safetyPerson' => 'Pusheen爸', 'traffic' => '自行開車', 
				'channel' => '144.51', 'channelName' => 'PP',
				'channelPeriod' => '偶數整點前後五分鐘', 'satellitePhone' => 'No', 'reference' => '2010 五寮尖', 
				'unregisteredMembers' => '某A 某B', 'memberRequire' => '自行練跑', 'memberComposition' => '男女比 : 2:1',
				 'fee' => '1500', 'importantDate' => '6/20行前',
				'profileSrc' => 'data/default/team/profile.jpg', 'greetings' => '誠徵班底!!!!', 'equipments' => '雪四 * 1'
			])			
		);
		
		
		$categories = Category::all();
		$zones = Zone::all();
		
		$articles = array(
			new Article(array('index' => 1, 'title' => '頭香~', 'content' => 'yoooo', 
				'team_id' => 1, 'category_id' => $categories[0]->id, 'zone_id' => $zones[0]->id)),
			new Article(array('index' => 1, 'title' => '頭香~~', 'content' => 'yoooooo', 
				'category_id' => $categories[2]->id, 'zone_id' => $zones[1]->id)),
			new Article(array('index' => 2, 'title' => '頭香~~', 'content' => 'yoooooo',
				'category_id' => $categories[3]->id, 'zone_id' => $zones[1]->id)),
			new Article(array('index' => 3, 'title' => '頭香~~', 'content' => 'yoooooo',
				'category_id' => $categories[4]->id, 'zone_id' => $zones[1]->id)),
		);
		
		$users = User::all();			
		$users->get(0)->articles()->saveMany($articles);		
		
		foreach ($teams as $team) {
			$team->createArticle(1);
		}
				
		$teams = Team::all();
		$teams->get(0)->initTeamWithPeople([1, 2], 1);
		$teams->get(1)->initTeamWithPeople([1, 2], 2);
		$teams->get(2)->initTeamWithPeople([1, 2], 2);
				
		foreach ($teams as $team) {
			$team->updateArticle();
		}
	
		$comment1 = new Comment(array('content' => '可惡沒搶到'));
		$comment2 = new Comment(array('content' => 'QQ'));		
		$comment1->user()->associate($users->get(1));
		$comment2->user()->associate($users->get(0));
		$articles[1]->comments()->saveMany(array($comment1, $comment2));
		
		$teams->get(0)->applyers()->attach(3, ['message' => '領隊我要爬山~~']);
		$teams->get(0)->applyers()->attach(4, ['message' => "我要報名\n好不好~~"]);
	}

}