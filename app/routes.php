<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showMain');

Route::get('login', 'UserController@showLogin');
Route::post('login', 'UserController@doLogin');
Route::get('logout', 'UserController@doLogout');

Route::get('register/verify/{confirmationCode}', 'UserController@confirm');
Route::get('register', 'UserController@showRegister');
Route::post('register', 'UserController@doRegister');

Route::get('user/{id}/edit', 'UserController@showEditUser');
Route::get('user/{id}', 'UserController@showUser');
Route::post('user/{id}/edit', 'UserController@doEditUser');
Route::get('user/forgetPassword/{email}', 'UserController@forgetPassword');
Route::get('user/resetPassword/{confirmationCode}', 'UserController@resetPassword');


Route::get('about', function() {	
	return View::make('about');
});

Route::post('team/create', 'TeamController@doCreateTeam');
Route::get('team/create', 'TeamController@showCreateTeam');
Route::get('team/{id}/delete', 'TeamController@deleteTeam');
Route::post('team/{id}/signUp', 'TeamController@signUp');
Route::post('team/{teamId}/signUp/{userId}/{isAccept}', 'TeamController@acceptOrReject');
Route::post('team/{id}/booleanProgress', 'TeamController@changeBooleanProgress');
Route::post('team/{id}/textProgress', 'TeamController@changeTextProgress');
Route::get('team/{id}', 'TeamController@showTeam');
Route::get('team', 'TeamController@showTeamList');
Route::get('privateTeam', 'TeamController@showPrivateTeamList');
Route::get('team/{id}/edit', 'TeamController@showEdit');
Route::post('team/{id}/edit', 'TeamController@doEdit');
Route::get('team/{id}/fullPlan', 'TeamController@showFullPlan');
Route::post('team/{id}/upload', 'TeamController@upload');
Route::get('team/delete/{id}', 'TeamController@delete');
Route::post('team/{id}/changeFilePublic', 'TeamController@changeFilePublic');
Route::get('team/{id}/openEnroll', 'TeamController@openEnroll');
Route::get('team/{id}/closeEnroll', 'TeamController@closeEnroll');

Route::get('readTeamFile/{id}/{file}', 'TeamController@download');

Route::get('article/create', 'ArticleController@showCreateArticle');
Route::post('article/create', 'ArticleController@doCreateArticle');
Route::get('article/{id}', 'ArticleController@showArticle');
Route::get('article', 'ArticleController@showArticleList');
Route::post('article/{id}/comment', 'ArticleController@addComment');
Route::get('article/{id}/edit', 'ArticleController@showEditArticle');
Route::post('article/{id}/edit', 'ArticleController@doEditArticle');
Route::get('article/{id}/delete', 'ArticleController@deleteArticle');
Route::get('ajax/article/{start}/{end}', 'ArticleController@ajaxShowList');

Route::get('collection', 'CollectionController@showCollection');
Route::get('search/teams/progress', 'CollectionController@getAllTeamsWithProgress');
Route::get('search/teams', 'CollectionController@getAllTeams');
Route::get('search', 'CollectionController@search');

/* Route::get('debug', 'DebugController@go'); */

if (file_exists(__DIR__.'/controllers/Server.php')) {
    Route::get('/server', 'Server@show');
    Route::get('/deploy', 'Server@deploy');
    Route::get('/db/refresh', 'Server@dbRefresh');
    Route::get('/db/reset', 'Server@dbReset');
    Route::get('/db/migrate', 'Server@dbMigrate');
    Route::get('/db/seed', 'Server@dbSeed');
}

View::composer(['home', 'user.login', 'user.register', 'user.show', 'user.edit'], function($view) {
	$view->with(['guideLink' => '', 'guideTitle' => '']);
});
View::composer(['about'], function($view) {
	$view->with(['guideLink' => 'about', 'guideTitle' => '關於']);
});
View::composer(['team.list', 'team.create', 'team.edit', 'team.fullPlan', 'team.show'], function($view) {
	$view->with(['guideLink' => 'team', 'guideTitle' => '隊伍']);
});
View::composer(['article.list', 'article.create', 'article.edit', 'article.show'], function($view) {
	$view->with(['guideLink' => 'article', 'guideTitle' => '討論版']);
});
View::composer(['collection.show'], function($view) {
	$view->with(['guideLink' => 'collection', 'guideTitle' => '精華區']);
});