<?php

namespace App\Magis\Traits\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait HasRevisions
{
    public function revisions()
    {
        $revisionTable = $this::SINGULAR.'_revisions';
        return DB::table($revisionTable)
                ->select(DB::raw($revisionTable.'.*, users.name AS username'))
                ->leftJoin('users', 'users.id', '=', $revisionTable.'.updated_by')
                ->where($revisionTable.'.id', '=', $this->id)
                ->orderBy($revisionTable.'.updated_at', 'desc')
                ->get()
                ->all();
    }

    public static function revision($id)
    {
        return DB::table(self::SINGULAR.'_revisions')
                ->where('revision_id', $id)
                ->first();
    }
}
