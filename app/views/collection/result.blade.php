
@if (isset($articles) && $articles->count() > 0)
	<section class='result-item'>
		<h1 class='title-m yellow'>討論版</h1>
		<article class='center middleWidth'>
			@foreach ($articles as $article)
				{{ View::make('reuse.media.article', ['media' => $article]) }}
			@endforeach
		</article>
	</section>
@endif

@if (isset($teams) && $teams->count() > 0)
	<section class='result-item'>
		<h1 class='title-m yellow'>行程</h1>
		<article class='center middleWidth'>
			@foreach ($teams as $team)
				{{ View::make('reuse.media.teamItem', ['team' => $team, 'content' => $team->plan])
					->nest('user', 'reuse.user', ['user' => $team->leaders[0]]) }}
			@endforeach
		</article>
	</section>
@endif

@if (isset($progresses))
	@foreach ($progresses as $progress)
		@if ($progress->count() > 0)
			<section class='result-item'>
				<h1 class='title-m yellow'>{{ $progress[0]->textProgresses[0]->name }}</h1>
				<article class='center middleWidth'>
					@foreach ($progress as $team)
						{{ View::make('reuse.media.teamItem', ['team' => $team, 'content' => $team->textProgresses[0]->pivot->content])
							->nest('user', 'reuse.user', ['user' => $team->leaders[0]]) }}
					@endforeach
				</article>
			</section>
		@endif
	@endforeach
@endif