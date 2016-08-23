<?php

namespace LiveCMS\Models;

use Carbon\Carbon;
use LiveCMS\Models\Core\PostableModel;
use LiveCMS\Models\Traits\AdminModelTrait;

class Gallery extends PostableModel
{
    use AdminModelTrait;

    protected $excepts = ['author_id', 'published_at'];

    protected $aliases = ['content' => 'Description'];
     
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
     
        $this->prefixSlug = getSlug('gallery');
    }

    public function rules()
    {
        $rules = parent::rules();

        return array_merge($rules, ['content' => '']);
    }
}
