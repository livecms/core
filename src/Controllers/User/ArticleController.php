<?php

namespace LiveCMS\Controllers\User;

use LiveCMS\Controllers\Backend\ArticleController as Senior;
use LiveCMS\Models\UserArticle as Model;
use LiveCMS\Models\Category;
use LiveCMS\Models\Tag;

class ArticleController extends Senior
{
    protected $groupName = 'user';

    public function __construct(Model $model, Category $category, Tag $tag, $base = 'myarticle')
    {
        parent::__construct($model, $category, $tag, $base);
        $this->model = $this->model->selfPost();
        $this->fields = array_except($this->fields, ['id', 'slug', 'author_id', 'category', 'tag', 'content']);
        $this->bodyClass = 'skin-blue sidebar-mini sidebar-collapse';

        $this->withoutHeader = true;
        // $this->model = $this->model->setAllSites(false);
    }
}
