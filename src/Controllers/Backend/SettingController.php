<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Controllers\BackendController;
use LiveCMS\Models\Core\Setting as Model;

class SettingController extends BackendController
{
    public function __construct(Model $model, $base = 'setting')
    {
        parent::__construct($model, $base);
        $this->model = $this->model->setAllSites(false);
        $this->breadcrumb2Icon  = 'cog';
        $this->fields           = array_except($this->model->getFields(), ['id']);
        
        $this->view->share();
    }
}
