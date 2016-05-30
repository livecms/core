<?php

namespace App\Models;

use LiveCMS\Models\BaseModel;
use LiveCMS\Models\Traits\AuthorModelTrait;

class Category extends BaseModel
{
    use AuthorModelTrait;

    protected $useAuthorization = false;

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
