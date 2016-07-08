<?php

namespace App\Models;

use LiveCMS\Models\PostableModel;
use LiveCMS\Models\Permalink;
use LiveCMS\Models\Traits\AuthorModelTrait;
use LiveCMS\Models\User;

class Article extends PostableModel
{
    use AuthorModelTrait;
    
    protected $mergesAfter = ['category' => 'Category', 'tag' => 'Tag'];

    protected $dependencies = ['author', 'categories', 'tags', 'permalink'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
     
        $this->prefixSlug = getSlug('article');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_categories', 'article_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id');
    }

    public function permalink()
    {
        return $this->morphOne(Permalink::class, 'postable');
    }
}
