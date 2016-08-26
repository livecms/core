<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Controllers\BackendController;
use LiveCMS\Models\ProjectCategory as Model;

class ProjectCategoryController extends BackendController
{
    public function __construct(Model $model, $base = 'projectcategory')
    {
        parent::__construct($model, $base);
        $this->breadcrumb2Icon  = 'list';
        $this->fields           = array_except($this->model->getFields(), ['id']);
        
        $this->view->share();
    }
}
