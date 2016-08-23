<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\BaseModel;
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
