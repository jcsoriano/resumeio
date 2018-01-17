<?php

namespace App\Http\Controllers\Magis;

use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MagisContentController extends MagisCategoryController
{
    protected $enableCaching = false;
    
    /**
     * Data array for Magis-table for Content
     *
     * @param  array  $new
     * @return array
     */
    protected function adminViewData($new = [])
    {
        return parent::adminViewData([
            // 'redirectAfterQuickAdd' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        // set the $item and $category variable first
        $item = null;
        $category = null;
        if (isset($this->category)) {
            // Unfortunately, this is inconsistent in Laravel.
            // If models are used as an object, relationships are still in camelCase.
            // However, when used as an array, they are converted to snake_case
            $category = camel_case($this->category);
        }

        // if caching is enabled and user not authenticated, use cache
        if ($this->enableCaching && ! Auth::check()) {
            // crc32 the key to prevent user-input attacks
            $keyHashed = crc32($key);

            // use the cached value if it exists
            if ($this->cacheTag()->has($keyHashed)) {
                return $this->cacheTag()->get($keyHashed);
            }

            // save the value to cache if it doesn't
            $item = $this->getItem($key, true);
            return $this->cacheTag()->remember(
                $keyHashed,
                $this->model::CACHE_EXPIRY,
                function () use ($item, $category) {
                    // here we go!
                    return $this->showView($item, $category)->render();
                }
            );
        }

        // authenticated; might be an admin
        $item = $this->getItem($key, true, $this->model::withoutGlobalScope('published'));

        // if draft or scheduled, check if user has required privileges
        if ($item->is_draft) {
            $this->authorize('draft', $item);
        }
        if (!empty($item->published_at) && Carbon::now()->lt($item->published_at)) {
            $this->authorize('schedule', $item);
        }

        // we've gotten this far; show it!
        return $this->showView($item, $category);
    }

    protected function showView($item, $category)
    {
        $resource = $this->model::SLUG;
        return view($this->indivView ?? 'magis/defaults/content/view', compact('item', 'category', 'resource'));
    }

    protected function cacheTag($type = 'view')
    {
        return Cache::tags([$this->model::SLUG, 'view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->expectsJson()) {
            return $this->filter();
        }

        $items = $this->model::paginate($this->perPage);
        return view($this->listView ?? 'magis/defaults/content/list', compact('items'));
    }

    public function attemptSearch(Request $request)
    {
        $this->requireSearchQuery($request);
        
        return redirect($this->model::SLUG. '/search/' . $request->q);
    }

    public function searchResults(Request $request, $keywords)
    {
        if (empty($q)) {
            redirect($this->model::SLUG);
        }

        $mainResource = $this->model::SLUG;
        $resource = $this->model::SLUG;
        $item = (object) ['name' => title_case($keywords)];
        $items = $this->model::search($keywords)->paginate($this->perPage);
        
        return view(
            $this->taxonomyView ?? 'magis/defaults/content/taxonomy',
            compact('item', 'items', 'mainResource', 'resource')
        );
    }

    private function requireSearchQuery(Request $request)
    {
        $this->validate($request, [
            'q' => 'required'
        ]);
    }
}
