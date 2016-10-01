<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\PostableModel;
use LiveCMS\Models\Core\Permalink;
use LiveCMS\Models\Traits\AdminModelTrait;

class Project extends PostableModel
{
    use AdminModelTrait;

    protected $fillable = ['title', 'site_id', 'slug', 'content', 'author_id', 'picture', 'client_id', 'status'];

    protected $excepts = ['author_id', 'client_id'];

    protected $mergesBefore = ['client' => 'Client'];
    
    protected $dependencies = ['categories', 'client', 'permalink'];

    protected $forms = [
        'tagged_multi_select' => ['categories'],
        'select' => ['client']
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
     
        $this->prefixSlug = getSlug('project');
    }

    public function rules()
    {
        $rules = parent::rules();

        return array_merge($rules, ['client' => 'required']);
    }

    public function categories()
    {
        return $this->belongsToMany(ProjectCategory::class, 'project_project_categories', 'project_id', 'category_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function permalink()
    {
        return $this->morphOne(Permalink::class, 'postable');
    }
}
