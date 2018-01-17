<?php

namespace App\Magis;

use App\User;
use Illuminate\Support\Str;
use App\Magis\Traits\Models\SavesSlug;
use Illuminate\Database\Eloquent\Model;
use App\Magis\Traits\Models\FlushesCache;
use App\Magis\Traits\Models\HasRevisions;
use App\Magis\Traits\Models\RecordsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Magis\Traits\Models\isMagisContentModel;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class Setting extends MagisModel
{
    use RecordsActivity, SavesSlug, SavesCreatedUpdatedBy, SoftDeletes, HasRevisions, FlushesCache;

    const PLURAL = 'settings';
    const SINGULAR = 'setting';
    const SLUG = 'settings';
    const TITLE = 'Settings';
    const MODULE = 'Magis';

    protected $fillable = ['name', 'content'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function editableFields(Setting $setting = null) : Array
    {
        return [
            'name' => 'string',
            'content' => 'text'
        ];
    }
}
