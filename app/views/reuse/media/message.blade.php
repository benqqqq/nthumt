<div class='media media-message'>	
	<time class='media-item media-smallTime'>{{ (new Date($media->created_at))->ago() }}</time>				
	<span class='media-item media-title gray'><span>{{{ $media->content }}}</span></span>
</div>