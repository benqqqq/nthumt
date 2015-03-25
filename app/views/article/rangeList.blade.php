@foreach ($articles as $article)
	{{ View::make('reuse.media.article', ['media' => $article]) }}
@endforeach		