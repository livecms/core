<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Models\Core\Permalink;
use LiveCMS\Models\Client;
use LiveCMS\Models\Project as Model;
use LiveCMS\Models\ProjectCategory;

class ProjectController extends PostableController
{
    protected $category;
    protected $client;
    protected $permalink;

    public function __construct(Model $model, ProjectCategory $category, Client $client, $base = 'project')
    {
        parent::__construct($model, $base);

        $this->unsortables = array_merge($this->unsortables, ['client', 'category']);

        $this->category = $category;
        $this->client = $client;

        $this->formLeftWidth = 2;
        $this->breadcrumb2Icon  = 'files-o';
        $this->fields           = array_merge($this->model->getFields(), ['category' => 'Category', 'client' => 'Client']);
        
        $this->view->share();
    }

    protected function processDatatables($datatables)
    {
        $datatables = parent::processDatatables($datatables);
        
        return $datatables
            ->addColumn('category', function ($data) {
                return dataImplode($data->categories, 'category');
            })
            ->addColumn('client', function ($data) {
                return $data->client->name;
            });
    }

    protected function loadFormClasses($model)
    {
        $this->categories   = $this->category->pluck('category', 'id')->toArray();
        $this->client       = $this->client->pluck('name', 'id')->toArray();
        
        parent::loadFormClasses($model);
    }

    protected function processRequest($request)
    {
        $client_id = $request->get('client', null);

        $request->merge(compact('client_id'));

        return parent::processRequest($request);
    }

    protected function afterSaving($request)
    {
        $categories = $request->get('categories', []);

        $newCategories = [];

        foreach ($categories as $index => $category) {
            if (is_numeric($category) && $this->category->find($category)) {
                continue;
            }

            $cat = $this->category->firstOrNew(['category' => $category]);

            if (!$cat->id) {

                $i = 0;
                do {
                    $slug = str_slug($cat->category).($i++ > 0 ? '-'.$i : '');
                } while ($this->category->where('slug', $slug)->first());

                $cat->slug = $slug;
                $cat->save();
            }

            $newCategories[$index] = $cat->id;
        }

        $categories = array_replace($categories, $newCategories);
        $request->merge(compact('categories'));

        $this->model->categories()->sync($request->get('categories', []));
        
        return parent::afterSaving($request);
    }
}
