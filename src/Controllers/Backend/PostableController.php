<?php

namespace LiveCMS\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use LiveCMS\Controllers\BackendController;
use LiveCMS\Models\Contracts\UserModelInterface;
use LiveCMS\Models\Core\PostableModel as Model;
use LiveCMS\Models\Core\Permalink;
use Upload;

abstract class PostableController extends BackendController
{
    protected $unsortables = ['picture', 'author_id'];
 
    public function __construct(Model $model, $base = 'post')
    {
        parent::__construct($model, $base);
        
        $this->breadcrumb2Icon  = 'file-o';
        $this->view->share();
    }

    protected function beforeDatatables($datas)
    {
        return $datas->with($this->model->dependencies());
    }

    protected function processDatatables($datatables)
    {
        return $datatables
            ->editColumn('title', function ($data) {
                return '<a target="_blank" href="'.$data->url.'">'.$data->title.'</a>';
            })
            ->editColumn('content', function ($data) {
                return str_limit(strip_tags($data->content), 300);
            })
            ->editColumn('author_id', function ($data) {
                return $data->author->name;
            })
            ->editColumn('picture', function ($data) {
                $imgUrl = $data->picture_small_cover;
                return $data->picture ? '<a target="_blank"  href="'.$imgUrl.'"><img src="'.$imgUrl.'" style="width: 100px;"></a>' : '-';
            })
            ->editColumn('published_at', function ($data) {
                return $data->published_at ? $data->published_at->diffForHumans() : '';
            })
            ->editColumn('status', function ($data) {
                return Str::title($data->status);
            });
    }

    protected function loadFormClasses($model)
    {
        $this->useCKEditor  = 'content';
     
        $this->view->share();
    }

    protected function processRequest($request)
    {
        $request = parent::processRequest($request);
        
        if ($request->has('permalink')) {
            
            if ($this->model->permalink !== null) {
            
                $this->validate($request, $this->model->permalink->rules());
            
            } else {

                $this->validate($request, (new Permalink)->rules());
            }
        }

        return $request;
    }

    protected function deletePicture($picture)
    {
        $directory = $this->model->getPicturePath();
        $picturePath = public_path($directory.DIRECTORY_SEPARATOR.$picture);

        @unlink($picturePath);
    }

    protected function afterSaving($request)
    {
        if (!in_array('LiveCMS\Models\Contracts\UserOnlyInterface', class_implements($this->model))) {

            if ($request->has('permalink')) {
                
                $permalink = $this->model->permalink;

                if ($permalink == null) {
                    $permalink = new Permalink();
                    $permalink->postable()->associate($this->model);
                    $permalink->save();
                }

                $permalink->update(['permalink' => $request->get('permalink')]);
            
            } else {

                if ($this->model->permalink) {
                    $this->model->permalink->delete();
                }
            }
        }

        $oldPicture = $this->model->picture;

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

            $object = $this->model;
            Upload::setFilenameMaker(function ($file, $object) {
                $title = $object->title ? $object->title : $object->name;
                return str_limit(str_slug($title.' '.date('YmdHis')), 200) . '.' . $file->getClientOriginalExtension();
            }, $object);

            Upload::model($object);

            $this->model->save();

            // $destinationPath = public_path($this->model->getPicturePath());

            // $extension = $request->file('picture')->getClientOriginalExtension();
            // $picture = str_limit(str_slug($this->model->title.' '.date('YmdHis')), 200) . '.' . $extension;
            
            // $result = $request->file('picture')->move($destinationPath, $picture);

            // if ($result) {
                
            //     $this->model->update(compact('picture'));
                
            //     $this->deletePicture($oldPicture);
            // }
        }

        if (empty($this->model->status)) {
            $status = Model::STATUS_DRAFT;
            $this->model->update(compact('status'));
        }

        return parent::afterSaving($request);
    }
}
