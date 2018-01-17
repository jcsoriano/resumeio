<?php

namespace App\Magis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MagisModel extends Model
{
    const CACHE_EXPIRY = 10080;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    const RELATIONS = [

    ];

    const RELATION_ATTRIBUTE_DISPLAY = [
        
    ];

    const DISABLE_ON_EDIT = [
        
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'drafted_at',
        'published_at',
    ];

    public static function manageColumns()
    {
        return [
            'name' => 'Name',
        ];
    }

    public static function manageColumnsDefault()
    {
        return ['name'];
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

    public static function scopeHasPhoto(Builder $query) : Builder
    {
        return $query->whereNotNull('photo')->where('photo', '!=', '');
    }

    public static function validationRules()
    {
        return [
            'name' => 'bail|required|max:255',
        ];
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
                    'link' => url(static::PLURAL.'/archives'),
                ]
            ];
        }
        return [];
    }

    public static function buttons()
    {
        return [];
    }

    public function getURL()
    {
        return url(str_plural(snake_case($this->getClassName())).'/'.$this->slug);
    }

    public function getClassName()
    {
        return class_basename($this);
    }

    public function getColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
