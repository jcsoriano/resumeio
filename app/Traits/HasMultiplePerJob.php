<?php

namespace App\Traits;

trait HasMultiplePerJob
{
    public static function findAllForJob($companySlug, $jobPostingSlug)
    {
        return static::where('companySlug', '=', $companySlug)
                ->where('jobPostingSlug', '=', $jobPostingSlug)
                ->get();
    }
}
