<?php

class CollectionController extends BaseController {

	
	public function showCollection() {
		return View::make('collection.show');	
	}
	
	public function search() {
		$word = Input::get('word');
		$words = explode(' ', $word);
		if ($word != '') {
			$articles = Article::where(function($query) use ($word, $words) {
				foreach($words as $w) {
					$query->where('title', 'like', '%' . $w . '%');
				}
				
				$query->orWhereHas('category', function($query) use ($word) {
					$query->where('name', 'like', '%' . $word . '%');
				});
			})
				->with('user', 'category')
				->orderBy('index', 'desc')->get();
			
			$teams = Team::where('name', 'like', '%' . $word . '%')->with(['leaders']);
			
			$traffics = $teams->with(['textProgresses' => function($query) {
				$query->where('name', '包車');
			}])->get();
			
			$menus = $teams->with(['textProgresses' => function($query) {
				$query->where('name', '菜單');
			}])->get();
			
			$progresses = [$traffics, $menus];
			
			$teams = $teams->get();
		}
		ArticleController::buildArticleRead($articles);
		return View::make('collection.result', ['articles' => $articles, 'teams' => $teams, 'progresses' => $progresses]);
	}
	
	public function getAllTeams() {
		$teams = Team::with(['leaders'])->get();
		return View::make('collection.result', ['teams' => $teams]);
	}
	public function getAllTeamsWithProgress() {
		$name = Input::get('name');
		$teams = Team::with(['leaders', 'textProgresses' => function($query) use ($name) {
				$query->where('name', $name);
		}])->get();
		return View::make('collection.result', ['progresses' => [$teams]]);
	}
	
}
