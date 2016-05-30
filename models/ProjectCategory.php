<?php

namespace App\Models;

use LiveCMS\Models\BaseModel;
use LiveCMS\Models\Traits\AdminModelTrait;

class ProjectCategory extends BaseModel
{
    use AdminModelTrait;

    protected $fillable = ['category', 'slug'];

    public function rules()
    {
        $this->slugify('category');

        return [
            'category' => $this->uniqify('category'),
            'slug' => $this->uniqify('slug'),
        ];
    }
}
