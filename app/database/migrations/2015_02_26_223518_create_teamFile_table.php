<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teamFiles', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->boolean('public')->default(false);
			$table->string('src');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();
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
		Schema::drop('teamFiles');
	}

}
