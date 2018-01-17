<?php

namespace App\Magis\Traits\Models;

use App\Magis\Activity;
use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        static::created(function ($model) {
            $model->recordActivity('created', null, $model->toJson());
        });

        static::deleting(function ($model) {
            $model->recordActivity('deleted', $model->fresh()->toJson(), null);
        });

        static::updating(function ($model) {
            $after = $model->getDirty();
            $model->recordActivity('updated', array_intersect_key($model->fresh()->toArray(), $after), $after);
        });
    }

    public function recordActivity($event, $before, $after)
    {
        $activity = new Activity;

        $activity->recordable_id = $this->id;
        $activity->recordable_type = get_class($this);
        $activity->before = $before;
        $activity->after = $after;
        $activity->name = $this->getActivityName($this, $event);
        $activity->user_id = Auth::check() ? Auth::user()->id : 0;

        $activity->save();
    }

    public function activities()
    {
        return $this->morphMany('App\Magis\Activity', 'recordable');
    }

    protected function getActivityName($model, $action)
    {
        $name = strtolower($this->getClassName($model));

        return "{$action}_{$name}";
    }

    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'deleted', 'updated'
        ];
    }
}
