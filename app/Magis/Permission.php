<?php

namespace App\Magis;

use Illuminate\Database\Eloquent\Model;
use App\Magis\Traits\Models\RecordsActivity;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class Permission extends MagisModel
{
    use SavesCreatedUpdatedBy;

    protected $fillable = ['role_id', 'label', 'option'];
    
    public function roles()
    {
        $this->belongsTo(Role::class);
    }
}
