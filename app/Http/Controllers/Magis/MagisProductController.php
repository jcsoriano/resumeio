<?php

namespace App\Http\Controllers\Magis;

use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

class MagisProductController extends MagisContentController
{
    /**
     * Display a listing of the drafts of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function drafts()
    {
        return $this->scoped('drafts', 'draft');
    }

    /**
     * Display a listing of the scheduled items in the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduled()
    {
        return $this->scoped('scheduled', 'schedule');
    }

    /**
     * Display a listing of the scheduled items in the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function published()
    {
        return $this->scoped('published', 'publish');
    }

    /**
     * Display a listing of the resource filtered by a tag
     *
     * @return \Illuminate\Http\Response
     */
    public function tag($tag)
    {
        return $this->taxonomy('tag', $tag);
    }

    /**
     * Display a listing of the resource filtered by a tag
     *
     * @return \Illuminate\Http\Response
     */
    public function author($author)
    {
        return $this->taxonomy('author', $author);
    }

    protected function taxonomy($taxonomy, $value)
    {
        $mainResource = $this->model::PLURAL;
        $resource = $this->model::PLURAL;
        $item = (object) ['name' => $value];
        $items = $this->model::$taxonomy($value)->get();
        return view($this->taxonomyView ?? 'magis/defaults/content/taxonomy', compact('item', 'items', 'mainResource', 'resource'));
    }

    /**
     * Controller logic for showing scopes in Magis-table
     *
     * @param  String $scope
     * @param  String $permission
     * @return Illuminate\Http\Response
     */
    protected function scoped(String $scope, String $permission)
    {
        $this->authorize($permission, new $this->model);

        if (request()->expectsJson()) {
            return $this->filter($this->model::withoutGlobalScope('published')->$scope());
        }

        return view('magis.admin.index', $this->adminViewData([
            'header' => $this->getViewHeader($this->model::SINGULAR, $scope),
            'noAdd' => true,
            'sourceUrl' => $this->model::PLURAL.'/'.$scope,
            'titleButtons' => [
                'title' => 'Back',
                'text' => '<span class="glyphicon glyphicon-chevron-left"></span> Back',
                'class' => 'btn btn-primary',
                'permission' => 'manage-'.$this->model::PLURAL,
                'link' => url($this->model::PLURAL.'/manage'),
            ],
        ]));
    }

    /**
     * Data array for Magis-table for Content
     *
     * @param  array  $new
     * @return array
     */
    protected function adminViewData($new = [])
    {
        return parent::adminViewData([
            'redirectAfterQuickAdd' => true,
            'noFull' => true,
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
        $item = $this->getItem($key, true, $this->model::withoutGlobalScope('published'));
        if ($item->is_draft) {
            $this->authorize('draft', $item);
        }
        if (Carbon::now()->lt($item->published_at)) {
            $this->authorize('schedule', $item);
        }
        
        if (isset($this->category)) {
            $category = $this->category;
        }
        return view($this->indivView ?? 'magis/defaults/content/view', compact('item', 'category'));
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

        $items = $this->model::all();
        return view($this->listView ?? 'magis/defaults/content/list', compact('items'));
    }
}
