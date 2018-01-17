<?php

namespace App;

use App\JobPosting;
use Illuminate\Support\Facades\App;
use Jenssegers\Mongodb\Eloquent\Builder;

class Company extends Resume
{
    protected $collection = 'resumes';

    protected static function boot()
    {
        static::addGlobalScope('companies', function (Builder $builder) {
            $builder->where('type', '=', 'company');
        });

        parent::boot();
    }

    public function findJobPosting($slug)
    {
        return $this->jobPostings()->where('slug', '=', $slug)->first();
    }

    public function findJobPostingOrFail($slug)
    {
        return $this->findJobPosting($slug) ?: App::abort(404, 'The Job Posting you\'re looking for may have been removed or does not exist');
    }

    public function jobPostings()
    {
        return $this->embedsMany(JobPosting::class);
    }
}
