<?php

namespace App\Services;

use App\Services\CleansingService;

class JobApplicationDashboardCleansingService extends CleansingService
{
    protected $fillableStructure = [
        'stages' => [
            'type' => 'array',
            'structure' => [
                'name' => [
                    'type' => 'string',
                ],
                'applicants' => [
                    'type' => 'array',
                    'structure' => [
                        'resumeSlug' => [
                            'type' => 'string',
                        ],
                        'user_id' => [
                            'type' => 'number',
                        ],
                        'rating' => [
                            'type' => 'number',
                        ],
                        'remarks' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ],
    ];
}
