<?php $resource = $resource ?? Request::segment(1); ?>
<header class="intro-header" style="background-image: url({{ asset(isset($item->photo) && !empty($item->photo) ? (str_contains($item->photo, 'googleusercontent') ? $item->photo.'=s1600' : $item->photo) : 'Magis/img/change.jpg') }})">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="post-heading">
                    <h1>
                        @if(isset($item))
                            {!! Content::title($item) !!}
                        @else
                            {{ Settings::value('site-title') }}
                        @endif
                    </h1>
                    <h2 class="subheading">{{ $item->excerpt ?? Settings::value('site-tagline') }}</h2>
                    @if(isset($item))
                        <span class="meta">
                            @include(Content::meta())
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>