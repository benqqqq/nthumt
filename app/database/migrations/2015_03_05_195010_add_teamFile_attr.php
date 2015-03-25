<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamFileAttr extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('teamFiles')->delete();
		foreach(Team::all() as $team) {
			TeamController::setFile($team);
		}
		
		Schema::table('teamFiles', function($table) {
			$table->integer('size')->unsigned()->default(0);
		});
		
		foreach(TeamFile::all() as $file) {			
			$id = preg_replace('/\/.*/', '', $file->src);
			$name = preg_replace('/default\//', '', $file->src);
			if ($id == 'default') {
				$src = app_path() . '/storage/data/default/team/' . $name;	
			} else {
				$src = app_path() . '/storage/data/team/' . $file->src;	
			}
						
			$file->size = File::size($src);
			$file->save();
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('teamFiles', function($table) {
			$table->dropColumn('size');
		});
	}

}
