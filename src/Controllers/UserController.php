<?php

namespace LiveCMS\Controllers;

use Illuminate\Http\Request;
use LiveCMS\Models\Contracts\BaseModelInterface as Model;

class UserController extends BackendController
{
    protected static $controllerModel;
    protected $model;
    protected $base;
    protected $baseClass;

    public function __construct(Model $model, $base = 'base')
    {
        parent::__construct($model, $base);

        $this->bodyClass        = 'skin-blue sidebar-mini sidebar-collapse';

        $this->breadcrumb1      = title_case(trans('livecms::livecms.home'));
        $this->breadcrumb1Icon  = 'home fa-lg';

        $this->breadcrumb2      = title_case(trans('livecms::livecms.'.$this->base));
        // $this->breadcrumb2Url   = route($this->baseClass.'.index');
        
        $this->view->share();
    }
}
