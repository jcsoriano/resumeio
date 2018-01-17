@if(isset($resume->profile['name']) && !empty($resume->profile['name']))
	<?php $title = $resume->profile['name']; ?>
@elseif(Auth::check() && !empty(Auth::user()->name))
	<?php $title = Auth::user()->name; ?>
@else
	<?php $title = Settings::value('site-title') . ' | ' . Settings::value('site-tagline'); ?>
@endif

@if(isset($resume) && !empty($resume->excerpt()))
	<?php $description = $resume->excerpt(); ?>
@else
	<?php $description = Settings::value('site-description'); ?>
@endif


@if(isset($resume->bannerPicture) && !empty($resume->bannerPicture))
	<?php $photo = $resume->bannerPicture; ?>
@elseif(isset($resume->profilePicture) && !empty($resume->profilePicture))
	<?php $photo = $resume->profilePicture; ?>
@else
	<?php $photo = ''; ?>
@endif

<title>{{ $title }}</title>
<meta property="og:title" content="{{ $title }}"/>
<meta name="og:site_name" content="{{ Settings::value('site-title') }}" />
<meta name="description" content="{{ $description }}" />
<meta name="og:description" content="{{ $description }}" />
<meta name="og:image" content="{{ url($photo) }}" />
