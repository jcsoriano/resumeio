<?php

namespace App\Magis\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasPhoto
{
    protected static function bootHasPhoto()
    {
        static::saving(function ($model) {
            $changedFields = array_intersect_key($model::editableFields($model), $model->getDirty());

            foreach ($changedFields as $key => $type) {
                $photo = $model->$key;

                // get the photos in this item that are not already in googleusercontent, but is in local storage
                if ($type == 'photo' && !empty($photo)) {
                    $aspectRatioColumn = $key.'_aspect_ratio';

                    if (strpos($photo, 'storage') === 0) {
                        $photo = storage_path(str_replace('storage', 'app/public', $photo));
                    }

                    list($width, $height) = getimagesize($photo);
                    $model->$aspectRatioColumn = $height / $width;
                }
            }
        });
    }

    public static function scopeHasPhoto(Builder $query) : Builder
    {
        return $query->whereNotNull('photo')->where('photo', '!=', '');
    }
}
