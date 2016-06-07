<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use LiveCMS\Controllers\BackendController;
use App\Models\Contact as Model;

class ContactController extends BackendController
{
    public function __construct(Model $model, $base = 'contact')
    {
        parent::__construct($model, $base);
        $this->breadcrumb2Icon  = 'phone';
        $this->fields           = array_except($this->model->getFields(), ['id']);
        
        $this->view->share();
    }

    public function index(Request $request)
    {
        $form = $this->create($request);
        
        $this->formLeftWidth = 2;
        $this->breadcrumb3  = $this->getTrans('edit');
        $this->view->share();
        return $form;
    }
}
