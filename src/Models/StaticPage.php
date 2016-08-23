<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\PostableModel;
use LiveCMS\Models\Traits\AdminModelTrait;

class StaticPage extends PostableModel
{
    use AdminModelTrait;

    protected $mergesBefore = ['id' => 'id', 'parent' => 'parent'];

    protected $excepts = ['id', 'parent_id'];

    protected $dependencies = ['permalink', 'author', 'parent'];
   
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, ['parent_id']);

        $this->aliases = array_merge($this->aliases, ['parent_id' => 'Parent']);

        $this->prefixSlug = getSlug('staticpage');

        parent::__construct($attributes);
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}
