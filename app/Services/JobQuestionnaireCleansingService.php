<?php

namespace App\Services;

use App\Services\ResumeCleansingService;

class JobQuestionnaireCleansingService extends ResumeCleansingService
{
    protected $fillableStructure = [
        'requireOnApply' => [
            'type' => 'boolean',
        ],
        'sections' => [
            'type' => 'function',
            'function' => 'cleanseSections',
        ],
    ];

    public function paragraphSectionStructure($section)
    {
        return [
            'description' => [
                'type' => 'object',
                'structure' => [
                    'text' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'image' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'question' => [
                'type' => 'boolean',
            ],
            'type' => [
                'type' => 'string',
            ],
        ];
    }

    public function ratingSectionStructure($section)
    {
        return [
            'data' => [
                'type' => 'array',
                'structure' => [
                    'rating' => [
                        'type' => 'number',
                    ],
                    'title' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'image' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'question' => [
                'type' => 'boolean',
            ],
            'type' => [
                'type' => 'string',
            ],
        ];
    }

    public function gallerySectionStructure($section)
    {
        return [
            'data' => [
                'type' => 'array',
                'structure' => [
                    'picture' => [
                        'type' => 'string',
                    ],
                    'title' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'image' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'type' => [
                'type' => 'string',
            ],
        ];
    }

    public function bulletSectionStructure($section)
    {
        return [
            'data' => [
                'type' => 'array',
                'structure' => [
                    'dateFrom' => [
                        'type' => 'string',
                    ],
                    'dateTo' => [
                        'type' => 'string',
                    ],
                    'description' => [
                        'type' => 'string',
                    ],
                    'title' => [
                        'type' => 'string',
                    ],
                    'answer' => [
                        'type' => 'stringOrArray',
                    ],
                    'question' => [
                        'type' => 'string',
                    ],
                    'type' => [
                        'type' => 'string',
                    ],
                    'choices' => [
                        'type' => 'array',
                        'structure' => [
                            'title' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
            'image' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'type' => [
                'type' => 'string',
            ],
            'question' => [
                'type' => 'boolean',
            ],
            'withDateRange' => [
                'type' => 'boolean',
            ],
        ];
    }

    public function linkSectionStructure($section)
    {
        return [
            'data' => [
                'type' => 'array',
                'structure' => [
                    'link' => [
                        'type' => 'string',
                    ],
                    'title' => [
                        'type' => 'string',
                    ],
                    'description' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'image' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'type' => [
                'type' => 'string',
            ],
        ];
    }
}
