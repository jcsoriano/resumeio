<?php

namespace App;

use App\Resume;
use App\Company;
use App\Magis\Role;
use App\JobApplication;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use App\Magis\Traits\Models\HasPhoto;
use App\Magis\Traits\Models\SavesSlug;
use Illuminate\Notifications\Notifiable;
use App\Magis\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use App\Magis\Traits\Models\RecordsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SavesSlug, SavesCreatedUpdatedBy, SoftDeletes, HasPhoto, HybridRelations, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo', 'name', 'email', 'password',
    ];

    protected $appends = [
        'name_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_by', 'created_at', 'updated_by', 'updated_at', 'verification_code'
    ];

    const PLURAL = 'users';
    const SINGULAR = 'user';
    const SLUG = 'users';

    const RELATIONS = [
        'roles' => 'many',
        'resumes' => 'many',
    ];

    const RELATION_ATTRIBUTE_DISPLAY = [
        
    ];

    const DISABLE_ON_EDIT = [
        'password'
    ];

    public static function manageColumns()
    {
        return [
            'photo' => 'Photo',
            'name' => 'Name',
            'email' => 'Email',
            'roles' => 'Roles',
        ];
    }

    public static function manageColumnsDefault()
    {
        return ['photo', 'name', 'roles'];
    }

    public static function manageColumnsRealTime()
    {
        return [];
    }

    /**
     * Sets whose images a person can see in the gallery
     *
     * Supported: own|all|[field]
     *  - own = instances created_by Auth::user()
     *  - all = all instances in this model
     *  - field = the field of this item must match the field in Auth::user()
     */
    public static function galleryPermission()
    {
        return 'own';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function company()
    {
        if ($this->is_company) {
            return $this->hasOne(Company::class);
        }
        // force an error if we're doing something wrong
        return null;
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles);
    }

    public static function editableFields(User $user = null)
    {
        return [
            'photo' => 'photo',
            'name' => 'string',
            'email' => 'email',
            'password' => 'password',
            'roles' => 'many-relation',
        ];
    }

    public function permissions()
    {
        // var_dump($this->roles);
        $permissions = $this->load('roles.permissions')->roles->pluck('permissions')->flatten()->toArray();
        // var_dump($permissions);
        $optionWeight = array_flip(PermissionService::OPTIONS);
        $uniquePermissions = [];
        foreach ($permissions as $p) {
            $label = $p['label'];
            $option = $p['option'];
            if (isset($uniquePermissions[$label])) {
                if ($optionWeight[$option] > $optionWeight[$uniquePermissions[$label]]) {
                    $uniquePermissions[$label] = $option;
                }
            } else {
                $uniquePermissions[$label] = $option;
            }
        }
        return $uniquePermissions;
    }

    public function getNameEmailAttribute()
    {
        return $this->name . ' (' . $this->email . ')';
    }

    public static function validationRules(User $user = null)
    {
        if (empty($user)) {
            return [
                'name' => 'bail|required|max:255',
                'password' => 'bail|required|max:255',
                'email' => 'bail|required|email|unique:users|max:255'
            ];
        } else {
            return [
                'name' => 'bail|required|max:255',
                'email' => [
                    'bail',
                    'required',
                    Rule::unique('users')->ignore($user->id),
                    'max:255'
                ]
            ];
        }
    }

    public function getURL()
    {
        return url(static::SLUG.'/'.$this->slug);
    }

    public function getClassName()
    {
        return class_basename($this);
    }

    public static function titleButtons()
    {
        $numArchives = static::onlyTrashed()->count();
        if ($numArchives > 0) {
            return [
                [
                    'title' => 'Archives',
                    'text' => '<span class="glyphicon glyphicon-trash"></span>',
                    'class' => 'btn btn-warning',
                    'permission' => 'restore-'.static::SINGULAR,
                    'link' => url(static::SLUG.'/archives'),
                ]
            ];
        }
        return [];
    }

    public static function buttons()
    {
        return [];
    }

    public static function scopeVerified(Builder $query) : Builder
    {
        return $query->where('verified', '=', 1);
    }

    public static function scopeCompanies(Builder $query) : Builder
    {
        return $query->where('is_company', '=', 1);
    }

    public static function scopeIndividuals(Builder $query) : Builder
    {
        return $query->where('is_company', '=', 0);
    }
}
