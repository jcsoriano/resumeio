<?php

namespace App\Magis;

use Illuminate\Database\Eloquent\Model;
use App\Magis\Traits\Models\RecordsActivity;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class Custompermission extends MagisModel
{
    use RecordsActivity, SavesCreatedUpdatedBy;
}
