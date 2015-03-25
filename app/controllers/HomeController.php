<?php

class HomeController extends BaseController {

	
	public function showMain() {
		$teams = Team::where('public', true)->orderBy('startDate', 'desc')->take(3)->get();
		$articles = $this->getLatestArticlesWithLimit(3);
		ArticleController::buildArticleRead($articles);
		return View::make('home', array('teams' => $teams, 'articles' => $articles, 'noGuide' => true));
	}
	
	private function getRunningTeams() {
		return Team::where('deadline', '>=', new DateTime('today'))->get();
	}
	
	private function getLatestArticlesWithLimit($limit) {				
		$articles = Article::where('zone_id', Zone::mainZone()->id)->orderBy('created_at', 'asc')->get()->toArray();
		$comments = Comment::whereHas('article', function($query) {
			$query->where('zone_id', Zone::mainZone()->id);
		})->orderBy('created_at', 'asc')->get()->toArray();
		
		$results = [];
		$resultIds = [];
		for ($i = 0; $i < $limit; ++$i) {
			if (count($articles)  == 0 && count($comments) == 0) {
				break;
			}
			$articleDate = count($articles) > 0 ? new Date(end($articles)['created_at']) : new Date('1900-01-01');
			$commentDate = count($comments) > 0 ? new Date(end($comments)['created_at']) : new Date('1900-01-01');
			
			$articleId = $articleDate > $commentDate ? array_pop($articles)['id'] : array_pop($comments)['article_id'];
			if (!in_array($articleId, $resultIds)) {
				array_push($resultIds, $articleId);	
				array_push($results, $this->getArticleWithHomeNeed($articleId));
			} else {
				--$i;
			}
		}
		return $results;
	}
	
	private function getArticleWithHomeNeed($id) {
		$article = Article::where('id', $id)->with(array('user', 'category', 'comments', 'comments.user'))->first();
		$skipCommentsNum = count($article->comments()->get()) - 2;
		if ($skipCommentsNum > 0) {
			$article->comments = $article->comments()->orderBy('created_at', 'asc')->skip($skipCommentsNum)->take(2)->get();	
		} else {
			$article->comments = $article->comments()->orderBy('created_at', 'asc')->get();
		}				
		return $article;
	}
	
}
