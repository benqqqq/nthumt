<div class='media media-comment'>
	{{ View::make('reuse.user', ['user' => $media->user]); }}	
	<time class='media-item media-smallTime {{ isset($class)? $class : "" }}'>{{ (new Date($media->created_at))->ago() }}</time>	
	
	<span class='media-item media-title'><span>{{{ $media->content }}}</span></span>
</div>