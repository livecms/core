<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\BaseModel;
use LiveCMS\Models\Traits\AdminModelTrait;

class ProjectCategory extends BaseModel
{
    use AdminModelTrait;

    protected $fillable = ['category', 'slug'];

    protected $forms = [
        'text' => ['category', 'slug']
    ];

    public function rules()
    {
        $this->slugify('category');

        return [
            'category' => $this->uniqify('category'),
            'slug' => $this->uniqify('slug'),
        ];
    }
}
