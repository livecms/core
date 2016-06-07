<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\ArticleController as Senior;
use App\Models\UserArticle as Model;
use App\Models\Category;
use App\Models\Tag;

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
