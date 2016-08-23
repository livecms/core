<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Models\Gallery as Model;

class GalleryController extends PostableController
{
    protected $permalink;

    public function __construct(Model $model, $base = 'gallery')
    {
        parent::__construct($model, $base);

        $this->breadcrumb2Icon  = 'image';
        $this->formLeftWidth = 2;

        $this->view->share();
    }
}
