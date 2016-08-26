<?php

namespace LiveCMS\Controllers\User;

use Illuminate\Http\Request;
use LiveCMS\Controllers\UserController;
use LiveCMS\Models\Core\Profile as Model;

class ProfileController extends UserController
{
    public function __construct(Model $model, $base = 'profile')
    {
        parent::__construct($model, $base);
        $this->model = $this->model->setAllSites(false);
        $this->breadcrumb2Icon  = 'user';
        
        $this->view->share();
    }

    public function index(Request $request)
    {
        $model = $this->model->find(auth()->user()->id);
        ${camel_case($this->base)} = $model;

        $this->title        = title_case(trans('livecms::livecms.'.$this->base));
        $this->description  = trans('livecms::livecms.homeprofile');
        $this->breadcrumb3  = trans('livecms::livecms.myprofile');
        $this->params       = array_merge($request->query() ? $request->query() : []);
        $this->action       = 'store';

        $this->view->share();

        return view('livecms::user.profile.home', compact(camel_case($this->base)));
    }

    public function store(Request $request)
    {
        $id = auth()->user()->id;

        return $this->update($request, $id);
    }

    protected function deletePicture($picture)
    {
        $directory = $this->model->getPicturePath();
        $picturePath = public_path($directory.DIRECTORY_SEPARATOR.$picture);
        
        @unlink($picturePath);
    }

    protected function afterSaving($request)
    {
        $result = [];

        $update = [];

        foreach (['avatar', 'background'] as $picture) {

            $oldPicture = $this->model->$picture;

            if ($request->hasFile($picture) && $request->file($picture)->isValid()) {

                $destinationPath = public_path($this->model->getPicturePath());

                $extension = $request->file($picture)->getClientOriginalExtension();
                $file = str_limit(str_slug($this->model->username.'-'.$picture.'-'.date('YmdHis').uniqid()), 200) . '.' . $extension;
                
                $success = $request->file($picture)->move($destinationPath, $file);

                if ($success) {
                    
                    $result[$oldPicture] = $file;

                    $update[$picture] = $file;
                }
            }
        }

        if (count($result)) {

            $this->model->update($update);

            foreach ($result as $oldPicture => $file) {
                
                $this->deletePicture($oldPicture);
            }
        }

        $successMessage = ucfirst(trans('livecms::livecms.updatesuccessmessage', [
            'model' => trans('livecms::livecms.'.($request->has('credentials') ? 'credential' : 'profile'))
        ]));

        alert()->success($successMessage, trans('livecms::livecms.updatesuccess'));

        return $this->model;
    }
}
