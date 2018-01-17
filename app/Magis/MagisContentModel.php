<?php

namespace App\Magis;

use App\Magis\MagisModel;

class MagisContentModel extends MagisModel
{
    protected $fillable = ['photo', 'name', 'author', 'content', 'tags', 'excerpt', 'is_draft', 'published_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'published_at',
        'drafted_at',
    ];

    const FIELD_LABELS = [
        'is_draft' => 'Save as Draft',
        'published_at' => 'Publish Schedule',
    ];

    const TABLE_LABELS = [
        'is_draft' => [
            'false_label' => [
                'label' => 'Published',
                'class' => 'success'
            ],
            'true_label' => [
                'label' => 'Draft',
                'class' => 'default'
            ],
            'false_button' => [
                'label' => 'Save as Draft',
                'class' => 'warning'
            ],
            'true_button' => [
                'label' => 'Publish',
                'class' => 'primary'
            ]
        ]
    ];

    const SEARCHABLE_FIELDS = [
        'id', 'name', 'author', 'content', 'tag', 'excerpt'
    ];

    public static function manageColumns()
    {
        return [
            'photo' => 'Photo',
            'name' => 'Name',
            'tags' => 'Tags',
            'author' => 'Author',
        ];
    }

    public static function manageColumnsDefault()
    {
        return ['photo', 'name'];
    }

    public static function validationRules()
    {
        return [
            'name' => 'bail|required|max:255',
            'tags' => 'bail|max:255',
            'excerpt' => 'bail|max:255',
        ];
    }

    public static function editableFields()
    {
        return [
            'photo' => 'photo',
            'name' => 'string',
            'content' => 'mediumText',
            'tags' => 'string',
            'excerpt' => 'string',
            'is_draft' => 'boolean',
        ];
    }

    public static function titleButtons()
    {
        $titleButtons = parent::titleButtons();
        $numPublished = static::count();
        $numDrafts = static::withoutGlobalScope('published')->drafts()->count();
        $numScheduled = static::withoutGlobalScope('published')->scheduled()->count();
        if ($numPublished > 0) {
            if ($numScheduled > 0) {
                array_unshift($titleButtons, [
                    'title' => 'Drafts',
                    'text' => '<span class="glyphicon glyphicon-pencil"></span> Scheduled <span class="label label-info">'.$numScheduled.'</span>',
                    'class' => 'btn btn-link',
                    'permission' => 'schedule-'.static::SINGULAR,
                    'link' => url(static::SLUG.'/scheduled'),
                ]);
            }
            if ($numDrafts > 0) {
                array_unshift($titleButtons, [
                    'title' => 'Drafts',
                    'text' => '<span class="glyphicon glyphicon-pencil"></span> Drafts <span class="label label-warning">'.$numDrafts.'</span>',
                    'class' => 'btn btn-link',
                    'permission' => 'draft-'.static::SINGULAR,
                    'link' => url(static::SLUG.'/drafts'),
                ]);
            }
            if ($numScheduled > 0 || $numDrafts > 0) {
                array_unshift($titleButtons, [
                    'title' => 'Published',
                    'text' => '<span class="glyphicon glyphicon-pencil"></span> Published <span class="label label-primary">'.$numPublished.'</span>',
                    'class' => 'btn btn-link',
                    'permission' => 'publish-'.static::SINGULAR,
                    'link' => url(static::SLUG.'/published'),
                ]);
            }
        }
        return $titleButtons;
    }

    public static function buttons()
    {
        return [
            'title' => 'Visual Edit',
            'text' => '<span class="glyphicon glyphicon-eye-open"></span>',
            'class' => 'btn btn-primary btn-xs',
            'permission' => 'update-'.static::SINGULAR,
            
            // workaround to the micromustache and VueJS attribute interpolation incompatibility
            'link' => url(static::SLUG.'/{___ slug ___}'),
        ];
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_only($this->toArray(), $this::SEARCHABLE_FIELDS);
    }
}
