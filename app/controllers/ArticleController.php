<?php

class ArticleController extends BaseController {

	
	public function showArticleList() {
		if (!Input::has('zone_id')) {
			$zone = Zone::mainZone();
		} else {
			$zone = Zone::find(Input::get('zone_id'));
		}
		$articles = Article::where('zone_id', $zone->id)->get();
		
		return View::make('article.list', ['articleNum' => $articles->count(), 'zone' => $zone]);
	}	
		
	public function showArticle($id) {
		$article = Article::with('comments', 'fullPlanTeam')->find($id);
		if (Auth::check()) {
			$user = $article->readUsers()->where('users.id', Auth::id())->first();
			if ($user == null) {
				$article->readUsers()->attach(Auth::id(), ['type' => 0]);	
			} else {
				$user->pivot->type = 0;
				$user->pivot->save();
			}			
		}
		return View::make('article.show', ['article' => $article]);
	}
	
	public function addComment($id) {
		$error = null;
		if (!Auth::check()) {		
			$error = '請先登入再留言';
		} else if (strlen(Input::get('content')) < 256) {
			$comment = new Comment(['content' => Input::get('content')]);
			$comment->user()->associate(Auth::user());				
			$article = Article::find($id);		
			$article->comments()->saveMany([$comment]);
			$this->modifyReadUser($article);
		} else {
			$error = '留言太長了哦';
		}		
		return $error;
	}
	
	private function modifyReadUser($article) {
		$users = $article->readUsers()->get();
		foreach($users as $user) {		
			$user->pivot->type = 1;
			$user->pivot->save();
		}		
	}
	
	public function showCreateArticle() {
		if (!Auth::check()) {
			return Redirect::back()->withMessage('請先登入');
		}
	
		$categories = Category::creatableCategory();
		$teamCategoryId = Category::teamCategory()->id;
		if (Input::has('team_id')) {
			$zone = Zone::teamZone();	
		} else {
			$zone = Zone::find(Input::get('zone_id'));	
		}
		return View::make('article.create', ['categories' => $categories, 'teamCategoryId' => $teamCategoryId, 'zone' => $zone]);
	}

	public function doCreateArticle() {
		$article = new Article(Input::all());			
		$article->user()->associate(Auth::user());		
		$article->setIndex();
		$article->save();
		Log::info('User ' . Auth::id() . ' add article ' . $article->id);
		return Redirect::to('article/' . $article->id);
	}
	
	public function showEditArticle($id) {
		$article = Article::with('user', 'category', 'zone')->find($id);
		if (!$this->isAuthor($article)) {
			return Redirect::to('/');
		}
		$categories = Category::creatableCategory();
		return View::make('article.edit', ['article' => $article, 'categories' => $categories]);		
	}
	
	private function isAuthor($article) {
		return User::isManager() || (Auth::id() == $article->user->id);
	}
	
	public function doEditArticle($id) {
		Article::find($id)->update(Input::all());
		return Redirect::to('article/' . $id);
	}
	
	public function deleteArticle($id) {
		$article = Article::with('user')->find($id);
		if (!$this->isAuthor($article)) {
			return Redirect::to('/');
		}		
		DB::transaction(function() use ($article) {
			$article->updateIndex();
			$article->delete();
		});	
		Log::info('User ' . Auth::id() . ' delete article ' . $article->id);
		return Redirect::to(Input::get('backUrl'));
	}
	
	public function ajaxShowList($start, $end) {
		$articles = Article::whereBetween('index', [$start, $end])
			->where('zone_id', Zone::find(Input::get('zone_id'))->id)
			->with('user', 'category', 'comments', 'zone')->orderBy('index', 'desc')->get();
			
		$this->buildArticleRead($articles);
		return View::make('article.rangeList', ['articles' => $articles]);		
	}
	
	public static function buildArticleRead($articles) {
		foreach($articles as $article) {
			if ($article->readUsers()->count() > 0) {
				$readUser = $article->readUsers()->find(Auth::id());
				if ($readUser != null) {
					$article->isRead = $readUser->type;	
				}
			}
		}
	}
}
