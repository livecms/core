<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Controllers\BackendController;
use LiveCMS\Models\Tag as Model;

class TagController extends BackendController
{
    public function __construct(Model $model, $base = 'tag')
    {
        parent::__construct($model, $base);
        $this->breadcrumb2Icon  = 'tag';
        $this->fields           = array_except($this->model->getFields(), ['id']);
        
        $this->view->share();
    }
}
