<?php

namespace App\Services;

use App\Services\CleansingService;

class ResumeCleansingService extends CleansingService
{
    protected $fillableStructure = [
        'profile' => [
            'type' => 'object',
            'structure' => [
                'name' => [
                    'type' => 'string',
                ],
                'position' => [
                    'type' => 'string',
                ],
            ],
        ],
        'bannerPicture' => [
            'type' => 'string',
        ],
        'profilePicture' => [
            'type' => 'string',
        ],
        'leftSnapshot' => [
            'type' => 'object',
            'structure' => [
                'title' => [
                    'type' => 'string',
                ],
                'items' => [
                    'type' => 'array',
                    'structure' => [
                        'title' => [
                            'type' => 'string',
                        ],
                        'description' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ]
        ],
        'rightSnapshot' => [
            'type' => 'object',
            'structure' => [
                'title' => [
                    'type' => 'string',
                ],
                'items' => [
                    'type' => 'array',
                    'structure' => [
                        'title' => [
                            'type' => 'string',
                        ],
                        'description' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ]
        ],
        'sections' => [
            'type' => 'function',
            'function' => 'cleanseSections',
        ],
    ];

    public function cleanseSections($sections)
    {
        return array_map(function ($section) {
            return $this->cleanseSection($section);
        }, $sections);
    }

    public function cleanseSection($section)
    {
        $structureFunction = camel_case($section['type']) . 'Structure';
        return $this->cleanseObject($section, $this->$structureFunction($section));
    }

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
