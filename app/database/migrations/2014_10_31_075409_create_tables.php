<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('name');			
			$table->string('password');
			$table->boolean('confirmed')->default(false);
			$table->string('profileSrc');
			$table->string('realName')->default('');
			$table->string('grade')->default('');
			
			$table->string('confirmationCode')->nullable();
			$table->timestamps();
			$table->rememberToken();			
		});
		
		Schema::create('messages', function($table) {
			$table->increments('id');
			$table->string('content');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');			
		});
		Schema::create('categories', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->text('content');
		});
		
		Schema::create('zones', function($table) {
			$table->increments('id');
			$table->string('name');
		});

		Schema::create('articles', function($table) {
			$table->increments('id');
			$table->integer('index')->unsigned()->default(1);			
			$table->string('title');
			$table->timestamps();
			$table->text('content');
			$table->integer('user_id')->unsigned();
			$table->integer('zone_id')->unsigned();
			$table->integer('category_id')->unsigned();
			//This one won't be binded with foreign key because only team articles will have
			$table->integer('team_id')->unsigned()->nullable()->default(null);					
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
		});	
						
		Schema::create('comments', function($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('article_id')->unsigned();	
			$table->string('content');
			$table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
		});
		
		Schema::create('teams', function($table) {			
			$table->increments('id');
			
			$table->integer('article_id')->unsigned();
			
			// infomation
			$table->string('name');
			$table->text('foreword');
			$table->text('intro');
			
			// plan
			$table->text('plan');			
			$table->date('startDate');
			$table->date('backDate');
			$table->datetime('backDatetime');
			$table->text('retreat');
			$table->datetime('deadline');
			$table->string('leftPerson');
			$table->string('safetyPerson');
			$table->string('traffic');
			$table->string('channel');
			$table->string('channelName');
			$table->string('channelPeriod');
			$table->string('satellitePhone');
			$table->text('reference');
			
			// people
			// 	team_user table
			$table->text('unregisteredMembers');
			$table->text('memberComposition');
			$table->text('memberRequire');
						
			// equipment
			$table->text('equipments');
			
			// other
			$table->string('fee');
			$table->text('importantDate');
			$table->string('profileSrc');
			$table->string('greetings');	
			
			$table->timestamps();		
			
			$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
		});
		
		Schema::create('progresses', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->boolean('isBoolean');
		});
		
		Schema::create('booleanProgress_team', function($table) {
			$table->increments('id');
			$table->integer('progress_id')->unsigned();
			$table->integer('team_id')->unsigned();			
			$table->boolean('isComplete')->default(false);
			$table->timestamps();
			//This one won't be binded with foreign key because only sometimes will have
			$table->integer('user_id')->unsigned()->nullable()->default(null);
			
			$table->foreign('progress_id')->references('id')->on('progresses')->onDelete('cascade');
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');						
		});	
		
		Schema::create('textProgress_team', function($table) {
			$table->increments('id');
			$table->integer('progress_id')->unsigned();
			$table->integer('team_id')->unsigned();			
			$table->text('content');
			$table->timestamps();
			//This one won't be binded with foreign key because only sometimes will have
			$table->integer('user_id')->unsigned()->nullable()->default(null);
			
			$table->foreign('progress_id')->references('id')->on('progresses')->onDelete('cascade');
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');						
		});	
		
		Schema::create('team_user', function($table) {
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();			
			$table->string('teamRole')->default('-');
			
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
		
		Schema::create('applies_team_user', function($table) {
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();			
			$table->text('message');
			$table->timestamps();
			
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
				
		
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('team_user');
		Schema::drop('applies_team_user');
		Schema::drop('booleanProgress_team');
		Schema::drop('textProgress_team');
		Schema::drop('progresses');
		Schema::drop('teams');		
		Schema::drop('comments');
		Schema::drop('articles');
		Schema::drop('categories');	
		Schema::drop('zones');
		Schema::drop('messages');	
		Schema::drop('users');
		
	}

}
