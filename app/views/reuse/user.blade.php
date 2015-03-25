<a href={{ URL::to('user/' . $user->id) }} class='user {{ isset($class)? $class : '' }}'>
	{{ HTML::image($user->profileSrc) }}
	<span>{{{ $user->name }}}</span>
</a>