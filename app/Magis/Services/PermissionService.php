<?php

namespace App\Magis\Services;

use App\Magis\Role;
use App\Magis\Custompermission;
use Illuminate\Support\Facades\Route;

class PermissionService
{
    /**
     * Roles that should be exempted from creating a permission for (e.g. Laravel forgot password routes)
     *
     * @var array
     */
    private static $exclude = [
        '/', 'password/reset/{token}'
    ];

    /**
     * Options for permissions
     *
     * @var array
     */
    const OPTIONS = [
        'own', 'all'
    ];

    /**
     * Action mappings for 'GET' routes
     *
     * @var array
     */
    private static $actionMap = [
        'edit' => 'update',
        'drafts' => 'draft',
        'archives' => 'restore',
        'scheduled' => 'schedule',
        'published' => 'publish',
    ];

    /**
     * Actions whose object pair should be plural in the permission label
     *
     * @var array
     */
    private static $plural = [
        'manage', 'list'
    ];

    /**
     * How actions are sorted
     *
     * @var array
     */
    private static $actionSort = [
        'list', 'view', 'manage', 'create', 'draft', 'schedule', 'update', 'delete', 'revert', 'restore'
    ];

    /**
     * Gets the objects from the routes
     *
     * @return array Objects obtained from routes
     */
    public static function objects()
    {
        $routes = Route::getRoutes()->getRoutesByMethod();
        $objects = [];
        foreach ($routes['GET'] as $path => $route) {
            if (count(explode('/', $path)) === 1) {
                $object = str_singular(ucfirst($path));
                $objects[$object] = $object;
            }
        }
        
        return $objects;
    }

    public static function has($permission)
    {
        return session('permissions')[$permission] ?? false;
    }

    /**
     * Gets Permissions from Routes (duh)
     *
     * @return array
     */
    public static function fromRoutes()
    {
        $routesByMethod = Route::getRoutes()->getRoutesByMethod();

        //remove exclude paths
        $routes = array_diff_key($routesByMethod['GET'], array_flip(self::$exclude));

        //infer permissions from all GET routes
        $permissions = [];
        $noActions = [];
        foreach ($routes as $path => $route) {
            //don't add routes with guest middleware
            if (!in_array('guest', $route->gatherMiddleware())) {
                list($object, $action, $segments) = self::extractSegments($path);

                if (count($segments) > 1) {
                    if (isset(self::$actionMap[$action])) {
                        $action = self::$actionMap[$action];
                    } elseif (self::actionIsParameter($action)) {
                        $action = 'view';
                    }
                    $permissions[$object][$action] = self::createLabel($object, $action);
                } else {
                    $noActions[$path] = $path;
                }
            }
        }

        //extract list permissions ('GET [object]' routes)
        $objects = array_intersect_key($noActions, $permissions);
        foreach ($objects as $object) {
            $permissions[$object]['list'] = self::createLabel($object, 'list');
        }

        //permissions for paths that don't map to an object
        $permissions['others'] = array_diff_key($noActions, $permissions);

        //'delete' permissions
        foreach ($routesByMethod['DELETE'] as $path => $route) {
            list($object, $action) = self::extractSegments($path);

            $permissions[$object]['delete'] = self::createLabel(explode('/', $path)[0], 'delete');
        }

        //custom permissions
        $customPermissions = Custompermission::all();
        foreach ($customPermissions as $cp) {
            $permission = $cp->name;
            list($action, $object) = self::extractSegments($permission, '-');
            $permissions[$object][$action] = $permission;
        }

        //sort permissions in objects based on actionSort + [others]
        $sortedPerObject = [];
        foreach ($permissions as $object => $permissionsInObject) {
            $sortedPerObject[$object] = self::sortPerObject($permissionsInObject);
        }

        //group by 'main' object
        $groupedObjects = [];
        foreach ($sortedPerObject as $object => $permissionsInObject) {
            $mainObject = self::objectIs(['categories', 'revisions'], $object);
            $groupedObjects[$mainObject ?: $object][$object] = $permissionsInObject;
        }

        return $groupedObjects;
    }

    /**
     * Creates a permission label
     *
     * @param  string $object
     * @param  string $action
     * @param  string $path
     * @return string         format: 'action-object'
     */
    private static function createLabel($object, $action)
    {
        $object = in_array($action, self::$plural) ? str_plural($object) : str_singular($object);
        return $action.'-'.$object;
    }

    /**
     * Checks if the action is a route parameter e.g. {post}
     *
     * @param  [type] $action Action to be checked
     * @return boolean         Whether the action is a route parameter
     */
    private static function actionIsParameter($action)
    {
        return $action[0] == '{' && substr($action, -1) == '}';
    }

    /**
     * Sort the permissions in an object based on self::$actionSort
     *
     * @param  array $permissionsInObject
     * @return array                      sorted permissions in object
     */
    private static function sortPerObject($permissionsInObject)
    {
        $sorted = [];
        foreach (self::$actionSort as $action) {
            if (isset($permissionsInObject[$action])) {
                $sorted[$action] = $permissionsInObject[$action];
            }
        }
        $others = array_diff($permissionsInObject, $sorted);
        return $sorted + $others;
    }

    /**
     * Extracts the segment in either a route path (e.g. posts/create) or a permission label ('create-post')
     *
     * @param  string $path      [description]
     * @param  string $separator Typically either '/' if route path or '-' if permission label
     * @return array            format: ['firstSegment', 'lastSegment', 'segments']
     */
    private static function extractSegments($path, $separator = '/')
    {
        $segments = explode($separator, $path);
        $first = str_replace('-', '_', $segments[0]);
        $last = end($segments);

        return [
            $first,
            $last,
            $segments
        ];
    }

    /**
     * Check if object is a sub-object of something.
     * @param  array|string $type  Sub-object type, typically 'archives', 'revisions', or 'categories'
     * @param  string $object Object to be checked on. E.g. 'postarchives'
     * @return string|boolean         The main object. False if object is not a sub-object of type $type.
     */
    private static function objectIs($type, $object)
    {
        if (is_array($type)) {
            foreach ($type as $a) {
                $a = '_'.$a;
                $len = strlen($a);
                if (substr($object, -$len) === $a) {
                    return str_plural(substr($object, 0, -$len));
                }
            }
            return false;
        }
        $type = '_'.$type;
        $len = strlen($type);
        return substr($object, -$len) === $type
            ? str_plural(substr($object, 0, -$len))
            : false;
    }

    /**
     * Get permissions related to object
     *
     * @param  string $object     Object whose related permissions you want to get
     * @param  array $fromRoutes Enter the routes here so we don't have to process permissions again
     * @return array             The permissions related to the object
     */
    public static function relatedTo($object, $fromRoutes = [])
    {
        if (empty($fromRoutes)) {
            $fromRoutes = collect(self::fromRoutes())->flatten()->toArray();
        }
        return array_filter($fromRoutes, function ($permission) use ($object) {
            $segments = explode('-', $permission);
            $permissionObject = end($segments);
            return $permissionObject == $object
                || $permissionObject == str_singular($object)
                || $permissionObject == str_plural($object);
        });
    }

    /**
     * Assigns permissions to a role
     *
     * @param  Role|int $role      The Role or its ID
     * @param  array $permissions  Permissions to be added to a role. Should have the ff. keys: ['label', 'option']
     * @return void
     */
    public static function assignTo($role, $permissions)
    {
        if (get_class($role) == Role::class) {
            $roleId = $role->id;
        } else {
            $roleId = $role;
            $role = Role::find($roleId);
        }
        $permissions = array_map(function ($item) {
            $item['role_id'] = $roleId;
            return $item;
        }, $permissions);

        // $role::insert($permissions);
        dispatch(new MassCreateOrUpdate(Permission, $permissions, ['role_id', 'label']));
    }

    /**
     * Shows the menu item IF currently logged-in user has the "manage-[object]" permission
     *
     * @param  [type] $menuItem
     * @return [type]
     */
    public static function showMenuItem($menuItem)
    {
        return isset(session('permissions')['manage-'.str_replace('-', '_', strtolower($menuItem->slug))]);
    }
}
