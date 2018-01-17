<div id="app" class="container">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
			@foreach($items as $item)
				<div class="post-preview">
		            <a href="{{ url($item->slug) }}">
		                <h2 class="post-title" >{{ $item->name }}</h2>
		                <h3 v-if="item.excerpt" class="post-subtitle" >
		                	{{ Content::excerpt($item) }}
		                </h3>
		            </a>
		            <p class="post-meta">
		                Posted 
		                @include(Content::meta())
		                {{-- <span v-if="item.author" v-text="'by ' + item.author"></span> 
		                <span v-if="item.published_at" v-text="'on ' + humanDate(item.published_at)"></span> --}}
		            </p>
		        </div>
		        <hr>
	        @endforeach

	        {{ $items->links() }}
		</div>
	</div>
</div>
