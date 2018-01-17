<?php

namespace App;

use App\User;
use App\Traits\ExtractsExcerpt;
use Illuminate\Support\Facades\App;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class JobPostingAttachment extends Moloquent
{
    use ExtractsExcerpt;
    
    protected $connection = 'mongodb';
    
    public static function findForJob($companySlug, $jobPostingSlug)
    {
        return static::where('companySlug', '=', $companySlug)
            ->where('jobPostingSlug', '=', $jobPostingSlug)
            ->first();
    }

    public static function createForJob($companySlug, $jobPostingSlug)
    {
        $jobAttachment = new static();
        $jobAttachment->companySlug = $companySlug;
        $jobAttachment->jobPostingSlug = $jobPostingSlug;
        $jobAttachment->save();

        if (defined(get_called_class() . '::CREATED_EVENT')) {
            $createdEvent = static::CREATED_EVENT;
            event(new $createdEvent($jobAttachment, get_called_class()));
        }

        return $jobAttachment->fresh();
    }

    public static function findForJobOrFail($companySlug, $jobPostingSlug)
    {
        return static::findForJob($companySlug, $jobPostingSlug) ?: App::abort(
            404,
            'The Job you\'re looking for may have been removed or does not exist'
        );
    }

    public static function findOrCreateForJob($companySlug, $jobPostingSlug)
    {
        return static::findForJob($companySlug, $jobPostingSlug)
            ?: static::createForJob($companySlug, $jobPostingSlug);
    }
}
