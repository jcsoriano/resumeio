<?php

namespace App\Http\Controllers\Magis;

use App\Http\Requests;
use Illuminate\Http\Request;

class MagisCategoryController extends MagisController
{
    /**
     * We use protected properties instead of const because these are
     * not meant to be accessed from outside
     */
    protected $perPage = 10;

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
        $items = $this->model::$taxonomy($value)->paginate($this->perPage);
        return view(
            $this->taxonomyView ?? 'magis/defaults/content/taxonomy',
            compact('item', 'items', 'mainResource', 'resource')
        );
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
     * Data array for Magis-table
     *
     * @param  array  $new
     * @return array
     */
    protected function adminViewData($new = [])
    {
        return array_merge(parent::adminViewData(), [
            'buttons' => $this->model::buttons()
        ], $new);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $mainResource = $this->mainResource;
        $item = $this->getItem($key, true)->load($mainResource);
        $items = $item->$mainResource()->paginate($this->perPage);
        return view($this->indivView ?? 'magis/defaults/content/taxonomy', compact('item', 'items', 'mainResource'));
    }
}
