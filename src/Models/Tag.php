<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\BaseModel;
use LiveCMS\Models\Traits\AuthorModelTrait;

class Tag extends BaseModel
{
    use AuthorModelTrait;

    protected $useAuthorization = false;
    
    protected $fillable = ['tag', 'slug'];

    protected $forms = [
        'text' => ['tag', 'slug']
    ];

    public function rules()
    {
        $this->slugify('tag');

        return [
            'tag' => $this->uniqify('tag'),
            'slug' => $this->uniqify('slug'),
        ];
    }
}
