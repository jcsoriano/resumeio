<article id="app">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1" >
                <div id="summernote-{{ $item->slug }}">
                	{!! Content::optimizeImages($item) !!}
                </div>
            </div>
        </div>
    </div>
</article>