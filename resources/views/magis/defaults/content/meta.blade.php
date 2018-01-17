@if(isset($item->author) || isset($item->published_at))
    Posted 
    @if(isset($item->author) && !empty($item->author))
        by <a href="{{ url($resource.'/author/'.$item->author) }}">{{ $item->author }}</a>
    @endif
    @if(isset($item->published_at) && !empty($item->published_at))
        on {{ $item->published_at->toDayDateTimeString() }}.
    @endif
@endif
@if(isset($category) && isset($item->$category) && !$item->$category->isEmpty())
    <small>
        <span class="glyphicon glyphicon-bookmark"></span> 
        Filed under:
        <?php $categories = $item->$category->map(function($val) use ($category) {
            return '<a href="'.url(snake_case($category) .'/'. $val['slug']).'">'.$val['name'].'</a>';
        }); ?>
        {!! implode(', ', $categories->toArray()) !!}
    </small>
@endif
&nbsp;
@if(isset($item->tags) && !empty($item->tags))
    <small>
        <span class="glyphicon glyphicon-tags"></span> 
        Tags:
        <?php $tags = array_map(function($val) use ($resource) {
            $val = trim($val);
            return '<a href="'.url($resource .'/tag/'. $val).'">'.$val.'</a>';
        }, explode(',', $item->tags)); ?>
        {!! implode(', ', $tags) !!}
    </small>
@endif