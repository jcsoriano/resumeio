<?php

namespace App\Http\Controllers\Magis;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Magis\Services\TypeService;
use App\Http\Controllers\Controller;
use App\Magis\Jobs\UploadPhotoToCloud;
use App\Magis\Traits\Controllers\ServesDatatable;

class MagisController extends Controller
{
    /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth')->except(['index', 'show', 'attemptSearch', 'searchResults']);
    }

    protected $headerTemplates = [
        'manage' => [
            'header' => '{capitalizedPluralObject}',
            'small' => 'Create, update, or delete a {lowercaseObject} here.'
        ],
        'create' => [
            'header' => 'Create New {capitalizedObject}',
            'small' => 'Save a new {lowercaseObject} to the system.'
        ],
        'archives' => [
            'header' => 'Manage Archives',
            'small' => 'View or Restore Archives here.'
        ],
        'drafts' => [
            'header' => 'Manage Drafts',
            'small' => 'Edit or Publish Drafts here.'
        ],
        'published' => [
            'header' => 'Manage Published {capitalizedPluralObject}',
            'small' => 'Edit Published {capitalizedPluralObject} here.'
        ],
        'update' => [
            'header' => 'Update {capitalizedObject}',
            'small' => 'Make changes to this {lowercaseObject}.'
        ],
        'profile' => [
            'header' => 'Edit Profile',
            'small' => 'Edit details in your profile.'
        ],
        'change-password' => [
            'header' => 'Change Password',
            'small' => 'Change this {capitalizedObject}\'s password.'
        ],
        'sort' => [
            'header' => 'Sort',
            'small' => 'Sort {capitalizedPluralObject} here.'
        ],
        'gallery' => [
            'header' => 'Gallery',
            'small' => 'Manage the {capitalizedObject}\'s gallery here.'
        ],
    ];

    protected function getViewHeader($object, $action)
    {
        $lowercaseObject = str_replace('_', ' ', $object);
        $pluralObject = str_plural($lowercaseObject);
        $capitalizedObject = title_case($lowercaseObject);
        $capitalizedPluralObject = str_plural($capitalizedObject);

        $lowercaseAction = strtolower($action);
        $pluralAction = strtolower(str_plural($action));
        $capitalizedAction = ucfirst($action);
        $capitalizedPluralAction = ucfirst(str_plural($action));

        $search = ['{capitalizedObject}', '{capitalizedPluralObject}', '{lowercaseObject}'];
        $replace = [$capitalizedObject, $capitalizedPluralObject, $lowercaseObject];

        return [
            'header' => str_replace($search, $replace, $this->headerTemplates[$action]['header']),
            'small' => str_replace($search, $replace, $this->headerTemplates[$action]['small']),
            'breadcrumbs' => [
                [
                    'icon' => '',
                    'name' => 'Admin',
                    'link' => '/'
                ],
                [
                    'icon' => '',
                    'name' => "{$capitalizedPluralObject}",
                    'link' => "/{$pluralObject}/manage"
                ],
                [
                    'icon' => '',
                    'name' => "{$capitalizedAction}",
                    'link' => "#"
                ]
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin()
    {
        // make sure logged-in
        $this->middleware('auth');

        // make sure authorized
        $this->authorize('manage', $this->model);

        // handle JSON request
        if (request()->expectsJson()) {
            return $this->filter($this->model::withoutGlobalScope('published'));
        }

        // show view file if not JSON request
        return view('magis.admin.index', $this->adminViewData());
    }

    /**
     * Data array for Magis-table
     *
     * @param  array  $new
     * @return array
     */
    protected function adminViewData($new = [])
    {
        return array_merge([
            'resource' => $this->model::SLUG,
            'title' => defined($this->model.'::TITLE') ? $this->model::TITLE : title_case($this->model::SLUG),
            'fieldLabels' => defined($this->model.'::FIELD_LABELS') ? $this->model::FIELD_LABELS : [],
            'header' => $this->getViewHeader($this->model::SINGULAR, 'manage'),
            'disableOnEdit' => $this->model::DISABLE_ON_EDIT,
            'titleButtons' => $this->model::titleButtons(),
            'buttons' => $this->model::buttons(),
            'sourceUrl' => ($this->model::SLUG).'/manage',
            'fieldLabels' => defined($this->model.'::FIELD_LABELS') ? $this->model::FIELD_LABELS : null,
            'form' => $this->model::editableFields(),
            'relationAttributes' => $this->model::RELATION_ATTRIBUTE_DISPLAY,
            'permissions' => session('permissions'),
            'columns' => $this->model::manageColumns(),
            'displayColumns' => $this->model::manageColumnsDefault(),
            'realTimeColumns' => $this->model::manageColumnsRealTime(),
            'typeMap' => TypeService::$map,
        ], $new);
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

        return redirect($this->model::SLUG.'/create');
    }

    protected function filter($scoped = null)
    {
        $scoped = $scoped ?: new $this->model;
        if (request()->has('forceLight')) {
            return [
                'items' => method_exists($scoped, 'all') ? $scoped::all() : $scoped->get()
            ];
        } elseif (request()->has('take')) { //heaveh
            $counter = with(clone $scoped);
            $query = $scoped->skip(request()->input('skip'))
                            ->take(request()->input('take'));
            if (request()->has('orderBy')) {
                $query->orderBy(request()->input('orderBy'), request()->input('orderDir'));
            }
            $search = request()->input('search');
            if (!empty($search)) {
                $editableFields = $this->model::editablefields();
                foreach ($this->model::manageColumns() as $databaseColumn => $humanLabel) {
                    // if many-relation, skip to the next column
                    if (isset($editableFields[$databaseColumn])
                        && $editableFields[$databaseColumn] == 'many-relation'
                    ) {
                        continue;
                    }

                    // if one-relation, change the column to '_id'
                    if (isset($editableFields[$databaseColumn])
                        && $editableFields[$databaseColumn] == 'one-relation'
                    ) {
                        $databaseColumn .= '_id';
                    }

                    // query that column!
                    $query->orWhere($databaseColumn, 'like', '%'.$search.'%');
                    $counter->orWhere($databaseColumn, 'like', '%'.$search.'%');
                }
            }
            $items = $this->loadRelations($query->get());
            $count = $counter->count();

            return [
                'items' => $items,
                'count' => $count
            ];
        } else {
            $recommendHeavy = $scoped->count() > config('magis.heavy_threshold');
            
            if ($recommendHeavy) {
                $items = [];
            } else {
                $items = $this->loadRelations($scoped->get());
            }

            return [
                'recommendHeavy' => $recommendHeavy,
                'items' => $items,
            ];
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archives()
    {
        if (request()->expectsJson()) {
            return $this->filter($this->model::withoutGlobalScope('published')->onlyTrashed());
        }

        return view('magis.admin.archives', [
            'resource' => $this->model::SLUG,
            'header' => $this->getViewHeader($this->model::SINGULAR, 'archives'),
            'archive' => true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', $this->model);
        if (request()->expectsJson()) {
            return ['form' => $this->model::editableFields()];
        }
        return view($this->form ?? 'magis/admin/form', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->model);
        return $this->save($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($key)
    {
        $item = $this->getItem($key, true, $this->model::withoutGlobalScopes());
        $this->authorize('update', $item);
        if (request()->expectsJson()) {
            return $this->editFormJson($item);
        }
        return view($this->form ?? 'magis/admin/form', $this->formData($item));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $key)
    {
        $item = $this->getItem($key, true, $this->model::withoutGlobalScopes());
        $this->authorize('update', $item);

        if ($request->has('onlyKey')) {
            return $this->onlyUpdateKey($request, $item);
        }

        return $this->save($request, $item);
    }

    protected function onlyUpdateKey(Request $request, $item)
    {
        $key = $request->input('onlyKey');
        $camelCaseKey = camel_case($key);
        $value = $request->input($key);

        // handle relations
        if (isset($this->model::RELATIONS[$camelCaseKey]) && $this->model::RELATIONS[$camelCaseKey] == 'many') {
            $oldValue = $item->$key;

            if ($this->handleManyRelation($value, $item, $camelCaseKey)) {
                // if relation was handled successfully, our work here is done!
                return [
                    'status' => 'success',
                    'oldValue' => $oldValue,
                    'newValue' => $value,
                ];
            }

            // something went wrong; this merits investigation
            return [
                'status' => 'error'
            ];
        }

        $newValue = [];
        $newValue[$key] = $value;
        $oldValue = $item->$key;

        // it's not a relation; let's check if it's a boolean, datetime, or one-relation
        if ($this->model::editableFields($item)[$key] == 'one-relation') {
            unset($newValue[$key]);
            $newValue[str_singular($key).'_id'] = is_array($value) ? $value['id'] : $value;
        } elseif ($this->model::editableFields($item)[$key] == 'boolean') {
            $newValue[$key] = $this->handleBoolean($request, $key);
        } elseif ($this->model::editableFields($item)[$key] == 'datetime') {
            $newValue[$key] = $this->handleDateTime($request, $key);
        }
        
        // now let's update the model!
        $item->update($newValue);

        // get the entire related object
        if ($this->model::editableFields($item)[$key] == 'one-relation') {
            $newValue[$key] = $item->fresh()->$key;
        }

        // tell 'em your success
        return [
            'status' => 'success',
            'oldValue' => $oldValue,
            'newValue' => $newValue[$key],
        ];
    }

    /**
     * Saves the item and its relations
     *
     * @param  Request   $request
     * @param  mixed|null $item
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $item = null)
    {
        $this->validate(
            $request,
            $this->model::validationRules($item)
        );
        
        $new = $request->all();
        $related = [];

        // convert relations to camelCase (when serialized, relations become snake_case)
        foreach ($this->model::RELATIONS as $relation => $type) {
            $snakeCaseRelation = snake_case($relation);
            if (isset($request[$snakeCaseRelation])) {
                // remove the relation from new, since we'll rename it depending on relationship type
                unset($new[$snakeCaseRelation]);

                if ($type == 'many') {
                    $related[$relation] = $request[$snakeCaseRelation];
                } elseif ($type == 'one') {
                    $new[str_singular($snakeCaseRelation).'_id'] = $request[$snakeCaseRelation];
                }
            }
        }

        // handle booleans and datetimes
        foreach ($this->model::editableFields($item) as $name => $type) {
            if ($type == 'boolean') {
                $new[$name] = $this->handleBoolean($request, $name);
            } elseif ($type == 'datetime') {
                $new[$name] = $this->handleDateTime($request, $name);
            }
        }

        if ($item) {
            $item->update($new);
        } else {
            $item = $this->model::create($new);
        }
        
        // get the fields that could have photos uploaded
        $fieldsWithPhotos = array_intersect($this->model::editablefields($item), ['photo', 'mediumText']);
        $hasPhoto = false;
        
        // check each field if there's a photo (not empty)
        foreach ($fieldsWithPhotos as $key => $fwp) {
            if (! empty($request->$key)) {
                $hasPhoto = true;
                break;
            }
        }

        // deploy a job that optimizes and uploads the photo to the cloud and deletes
        // the temporary local file
        if ($hasPhoto) {
            dispatch(new UploadPhotoToCloud($item));
        }

        foreach ($this->model::RELATIONS as $relation => $type) {
            if ($type == 'many' && isset($related[$relation])) {
                $this->handleManyRelation($related[$relation], $item, camel_case($relation));
            }
        }
        
        if (request()->expectsJson()) {
            return [
                'status' => 'success',
                'redirect' => url($this->model::SLUG.'/'.$item->slug)
            ];
        }
        return redirect($this->model::SLUG.'/manage');
    }

    protected function handleManyRelation($value, $item, $camelCaseKey)
    {
        // check if $value is a 2D array
        if (count($value) > 0
            && isset($value[0])
            && !is_int($value[0])
        ) {
            // if it's a 2D array, it's an array of objects; just get the IDs
            if (is_array($value[0])) {
                $value = array_pluck($value, 'id');
            }
        }

        // sync the IDs
        if (is_array($value)) {
            $item->$camelCaseKey()->sync($value);

            // our job here is done
            return true;
        }

        return false;
    }

    protected function handleBoolean(Request $request, $name)
    {
        return $request->has($name) && $request->input($name)
            ? $request->input($name)
            : false;
    }

    protected function handleDateTime(Request $request, $name)
    {
        return $request->has($name) && !empty($request->input($name))
            ? $request->input($name)
            : null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $item = $this->getItem($key);
        return redirect(url()->current().'/edit');
    }

    /**
     * Gets an item based on the key
     *
     * @param  String|null $key     The key, usually slug or ID
     * @param  boolean $eagerLoad   Whether to eager load its relations
     * @param  [type] $scoped       The scope where to search from
     * @return Collection|Model
     */
    protected function getItem(String $key = null, $eagerLoad = false, $scoped = null)
    {
        $scoped = $scoped ?: new $this->model;
        
        if ($key) {
            $result = $scoped->where(with(new $this->model)->getRouteKeyName(), $key)->firstOrFail();
            if ($eagerLoad) {
                return $this->loadRelations($result);
            }
            return $result;
        }
        return new $this->model;
    }

    /**
     * Json response for edit forms (called by edit and revision)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editFormJson($item)
    {
        $editableFields = $this->model::editableFields($item);
        return [
            'form' => $editableFields,
            'values' => array_intersect_key($item->toArray(), $editableFields),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($key)
    {
        $item = $this->getItem($key, false, $this->model::withoutGlobalScopes());
        $this->authorize('delete', $item);
        $item->delete();
        if (request()->expectsJson()) {
            return ['status' => 'success'];
        }
        return redirect($this->name.'/manage');
    }

    /**
     * Restore the specified resource from archives
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function revision($revisionId)
    {
        $revision = (array) $this->model::revision($revisionId);
        $item = $this->model::findOrFail($revision['id']);
        $this->authorize('restore', $item);
        unset($revision['revision_id']);
        foreach ($revision as $key => $value) {
            $item->$key = $revision[$key];
        }

        if (request()->expectsJson()) {
            return $this->editFormJson($item);
        }
        return view($this->form ?? 'magis.admin.form', $this->formData($item));
    }

    /**
     * Restore the specified resource from archives
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($key)
    {
        $item = $this->getItem($key, false, $this->model::onlyTrashed());
        $this->authorize('restore', $item);
        $item->restore();
        if (request()->expectsJson()) {
            return ['status' => 'success'];
        }
        return redirect($this->name.'/manage');
    }

    /**
     * Get the data used by the form in create and update
     *
     * @param  string $key The key to the object
     * @return array       The array of values needed by the form
     */
    protected function formData($item = null)
    {
        $current = $item ? $item->fresh() : null;
        return [
            'header' => $this->getViewHeader($this->model::SINGULAR, ($item ? 'update' : 'create')),
            'action' => $item ? $current->getURL() : $this->model::SLUG,
            'current' => $item ? $current : null,
            'method' => $item ? 'PUT' : 'POST',
            'slug' => $item->slug ?? '',
            'id' => $item->id ?? 'new',
            'values' => $item ? array_intersect_key($item->toArray(), $this->model::editableFields($item)) : null,
            'form' => $this->model::editableFields($item),
            'relationAttributes' => $this->model::RELATION_ATTRIBUTE_DISPLAY,
            'resource' => $this->model::SLUG,
            'fieldLabels' => defined($this->model.'::FIELD_LABELS') ? $this->model::FIELD_LABELS : null,
            'disableOnEdit' => $item ? $this->model::DISABLE_ON_EDIT : [],
            'revisions' => $item && method_exists($item, 'revisions') ? $item->revisions() : null,
        ];
    }

    /**
     * Returns either an eager-loaded or new empty instance of the model (so you can call it with non-statically)
     *
     * @return mixed
     */
    protected function eagerLoad()
    {
        if (!empty($this->model::RELATIONS)) {
            return $this->model::with(array_keys($this->model::RELATIONS));
        } else {
            return new $this->model;
        }
    }

    /**
     * Loads the relations on an Eloquent Collection
     *
     * @param  $collection
     * @return mixed
     */
    protected function loadRelations($collection)
    {
        if (!empty($this->model::RELATIONS)) {
            return $collection->load(array_keys($this->model::RELATIONS));
        } else {
            return $collection;
        }
    }

    /**
     * Returns all images in a directory
     *
     * @param  Request $request
     * @return array
     */
    public function gallery(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        // if GALLERY_PERMISSION constant is not defined, gallery is not supported by this model
        if (!defined($this->model.'::galleryPermission()')) {
            return [
                'status' => 'success',
                'files' => [],
            ];
        }

        $files = [];

        if ($this->model::galleryPermission() == 'own') {
            $files = $this->model::mine()->hasPhoto()->select('photo', 'photo_aspect_ratio')->get();
        } elseif ($this->model::galleryPermission() == 'all') {
            $files = $this->model::hasPhoto()->select('photo', 'photo_aspect_ratio')->get();
        } else {
            // that's weird; throw an error
            return [
                'status' => 'error',
                'message' => 'Gallery permission not supported'
            ];
        }

        return [
            'status' => 'success',
            'files' => $files,
        ];
    }
}
