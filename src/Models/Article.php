<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\PostableModel;
use LiveCMS\Models\Core\Permalink;
use LiveCMS\Models\Traits\AuthorModelTrait;
use LiveCMS\Models\Core\User;

class Article extends PostableModel
{
    use AuthorModelTrait;

    protected $fillable = ['title', 'site_id', 'slug', 'content', 'author_id', 'picture', 'published_at', 'status', 'is_featured'];

    protected $mergesAfter = ['category' => 'Category', 'tag' => 'Tag'];

    protected $aliases = ['author_id' => 'author', 'is_featured' => 'fa-star'];

    protected $dependencies = ['author', 'categories', 'tags', 'permalink'];

    protected $forms = [
        'text' => ['title', 'slug'],
        'tagged_multi_select' => ['categories', 'tags'],
        'boolean' => ['is_featured'],
    ];

    protected $casts = ['is_featured' => 'boolean'];

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
