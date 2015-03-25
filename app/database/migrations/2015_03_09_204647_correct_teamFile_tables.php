<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectTeamFileTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		foreach(Team::all() as $team) {
			TeamController::writePlanFile($team);
			// Create plan directory
			$dir = app_path() . '/storage/data/team/' . $team->id;
			if (!File::exists($dir)) {
				File::makeDirectory($dir);
			}
			// Insert plan db	
			TeamFile::create(['name' => '計劃書', 'src' => $team->id . '/fullPlan.txt', 'public' => true,
				'team_id' => $team->id, 'user_id' => User::manager()->id]);
		}	
		foreach(TeamFile::all() as $file) {		
			if ($file->size != 0) {
				continue;
			}	
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
		//
	}

}
