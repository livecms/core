<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Controllers\BackendController;
use LiveCMS\Models\Core\Permalink as Model;

class PermalinkController extends BackendController
{
    public function __construct(Model $model, $base = 'permalink')
    {
        parent::__construct($model, $base);

        $edited = ['title' => 'title', 'postable_type' => 'type', 'postable_id' => 'id'];
        
        $this->breadcrumb2Icon  = 'link';
        $this->fields           = array_merge(array_except($this->model->getFields(), ['id']), $edited);
        $this->withoutAddButton = true;
        
        $this->view->share();

    }

    protected function beforeDatatables($datas)
    {
        return $datas->with($this->model->dependencies());
    }

    protected function processDatatables($datatables)
    {
        return $datatables
            ->editColumn('permalink', function ($data) {
                $url = $data->postable->url;
                return '<a target="_blank"  href="'.$url.'">'.$url.'</a>';
            })
            ->editColumn('postable_type', function ($data) {
                $type = trans('livecms::livecms.'.strtolower($data->type));
                return $type;
            })
            ->addColumn('title', function ($data) {
                return
                    $data->postable->title.
                    ' <a href="'.action(config('livecms.routing.namespace').'\\Backend\\'.$data->type.'Controller@edit', ['id' => $data->postable->id]).
                    '"><i class="fa fa-pencil"></i></a>';
            });
    }
}
