<?php

namespace App;

use App\Resume;
use App\JobApplication;

class JobPosting extends Resume
{
    protected $collection = 'job_postings';
}
