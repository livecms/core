<?php

namespace LiveCMS\Controllers\Backend;

use LiveCMS\Models\Team as Model;
use LiveCMS\Models\TeamMediaSocial;

class TeamController extends PostableController
{
    protected $permalink;

    protected $mediasocial;

    protected $socials;

    protected $mediasocials;

    public function __construct(Model $model, TeamMediaSocial $mediasocial, $base = 'team')
    {
        parent::__construct($model, $base);

        $this->mediasocial = $mediasocial;
        $this->formLeftWidth = 2;
        $this->socials = array_combine($mediasocial->socials(), array_map('title_case', $mediasocial->socials()));
        $this->breadcrumb2Icon  = 'user-plus';

        $this->view->share();
    }

    protected function afterSaving($request)
    {
        $socials = $request->get('socials');


        foreach ($socials as $social => $url) {
            
            $mediasocial = $this->model->socials()->where('social', $social)->first();

            if ($mediasocial == null) {
                $this->model->socials()->save(new $this->mediasocial(compact('social', 'url')));
            } else {
                $mediasocial->update(compact('social', 'url'));
            }
        }

        return parent::afterSaving($request);
    }

    public function loadFormClasses($model)
    {
        parent::loadFormClasses($model);

        $this->socials = $this->model->socials;
    }
}
