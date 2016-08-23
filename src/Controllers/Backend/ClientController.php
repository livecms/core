<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Models\Client as Model;

class ClientController extends PostableController
{
    public function __construct(Model $model, $base = 'client')
    {
        parent::__construct($model, $base);

        $this->breadcrumb2Icon  = 'user-plus';
        $this->formLeftWidth = 2;

        $this->view->share();
    }

    protected function processDatatables($datatables)
    {
        $datatables = parent::processDatatables($datatables);
        
        return $datatables
            ->addColumn('project', function ($data) {
                return rtrim(implode(', ', $data->projects->pluck('title')->toArray()), ', ');
            });
    }
}
