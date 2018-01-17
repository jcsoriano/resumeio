<?php

namespace App\Services;

use App\Services\JobQuestionnaireCleansingService;

class JobQuestionnaireAnswerCleansingService extends JobQuestionnaireCleansingService
{
    protected $fillableStructure = [
        'sections' => [
            'type' => 'function',
            'function' => 'cleanseSections',
        ],
    ];
}
