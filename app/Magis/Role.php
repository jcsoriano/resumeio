<?php

namespace App\Magis;

use App\User;
use Illuminate\Support\Str;
use App\Magis\Traits\Models\SavesSlug;
use Illuminate\Database\Eloquent\Model;
use App\Magis\Traits\Models\HasRevisions;
use App\Magis\Traits\Models\RecordsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Magis\Traits\Models\isMagisContentModel;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class Role extends MagisModel
{
    use RecordsActivity, SavesSlug, SavesCreatedUpdatedBy, SoftDeletes, HasRevisions;

    const PLURAL = 'roles';
    const SINGULAR = 'role';
    const SLUG = 'roles';

    protected $fillable = ['name', 'description'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function manageColumns()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    public static function manageColumnsDefault()
    {
        return ['name', 'description'];
    }

    public static function editableFields(Role $role = null)
    {
        return [
            'name' => 'string',
            'description' => 'text'
        ];
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Sets permission of a role through a permission key-value pair
     * @param array   $permissionKeyValuePair Format should be: ['label' => 'option']
     * @param boolean $delete                 Whether previous permissions attached to role should be deleted first
     */
    public function setPermissions(array $permissionKeyValuePair, $delete = true)
    {
        if ($delete) {
            $this->permissions()->delete();
        }
        $this->permissions()->createMany(
            array_map(function ($label, $option) {
                return compact('label', 'option');
            }, array_keys($permissionKeyValuePair), $permissionKeyValuePair)
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function assignTo(User $user)
    {
        $this->users()->attach($user->id);
    }
}
