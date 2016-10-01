<?php

namespace LiveCMS\Controllers;

use Form;
use Datatables;
use ReflectionClass;
use App\Http\Requests;
use Illuminate\Http\Request;

use LiveCMS\Models\Contracts\BaseModelInterface as Model;

class BackendController extends BaseController
{
    protected static $controllerModel;
    protected $model;
    protected $base;
    protected $baseClass;

    protected $groupName = 'admin';

    public function __construct(Model $model, $base = 'base')
    {
        $this->middleware('model:'.get_class($model));
        parent::__construct();

        $this->withoutHeader = true;
        $this->model    = static::$controllerModel = $model;
        $this->base     = $base;
        $reflection     = new ReflectionClass($this);
        $this->baseClass = '\\'.$reflection->getName();

        $this->fields           = $this->model->getFields();
        $this->breadcrumb2      = title_case($this->getTrans($this->base));
        // $this->breadcrumb2Url   = route($this->baseClass.'.index');
        
        $this->view->share();
    }

    protected function getTrans($word, $clues = [])
    {
        return trans('livecms::'.($this->groupName ?: 'livecms').'.'.$word, $clues);
    }

    public function getControllerModel()
    {
        return static::$controllerModel;
    }

    protected function processDatatables($datatables)
    {
        return $datatables;
    }

    protected function processRequest($request)
    {
        return $request;
    }

    protected function loadFormClasses($model)
    {
        //
    }

    protected function afterSaving($request)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        $action = strtolower($trace[1]['function'] != 'afterSaving' ?$trace[1]['function'] : $trace[2]['function']);

        $title = ucfirst(trans('livecms::livecms.'.$action.'success'));
        $message = ucfirst(trans('livecms::livecms.'.$action.'successmessage', ['model' => trans('livecms::livecms.'.$this->base)]));
        alert()->success($message, $title);

        return $this->model;
    }

    protected function redirection($method = 'index')
    {
        if (request()->wantsJson()) {
            return $this->data(request());
        }

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return redirect()->action($this->baseClass.'@'.$method);
    }

    public function index(Request $request)
    {
        $this->title        = title_case($this->getTrans($this->base));
        $this->description  = $this->getTrans('alllist', ['list' => title_case($this->getTrans($this->base))]);
        $this->breadcrumb3  = $this->getTrans('seeall');
        $this->params       = array_merge($request->query() ? $request->query() : []);

        $this->view->share();

        return view('livecms::'.$this->groupName.'.partials.appIndex');
    }

    protected function getDataFields()
    {
        return [null => $this->model->getKeyName()]+$this->model->getFillable();
    }

    protected function beforeDatatables($datas)
    {
        return $datas;
    }

    protected function drawMenuField($data)
    {
        return
            '<a href="'.action($this->baseClass.'@edit', [$data->{$this->model->getKeyName()}]).'" 
                class="btn btn-small btn-link">
                    <i class="fa fa-xs fa-pencil"></i> 
                    Edit
            </a> '.
            Form::open(['style' => 'display: inline!important', 'method' => 'delete',
                'action' => [$this->baseClass.'@destroy', $data->{$this->model->getKeyName()}]
            ]).
            '  <button type="submit" onClick="return confirm(\''.$this->getTrans('deleteconfirmation').'\');" 
                class="btn btn-small btn-link">
                    <i class="fa fa-xs fa-trash-o"></i> 
                    Delete
            </button>
            </form>';
    }

    public function data(Request $request)
    {
        $datas = $this->model->select($this->getDataFields());
        
        $datas = $this->beforeDatatables($datas);

        $datatables = Datatables::of($datas)->addColumn('menu', function ($data) {
            return $this->drawMenuField($data);
        });

        $datatables = $this->processDatatables($datatables);
        $result = $datatables
            ->make(true);

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = $this->model;
        ${camel_case($this->base)} = $model;

        $this->title        = $this->getTrans('adddata', ['data' => title_case($this->getTrans($this->base))]);
        $this->description  = $this->getTrans('addingdata', ['data' => $this->getTrans($this->base)]);
        $this->breadcrumb3  = $this->getTrans('add');
        $this->action       = 'store';
        $this->params       = array_merge($request->query() ? $request->query() : []);

        $this->view->share();

        $this->loadFormClasses($model);

        return view('livecms::'.$this->groupName.'.'.camel_case($this->base).'.form', compact(camel_case($this->base)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $this->processRequest($request);

        if ($request === true) {
            return $this->redirection();
        }

        $this->validate($request, $this->model->rules());

        $this->model = $this->model->create($request->all());

        $saved = $this->afterSaving($request);

        if ($saved) {
            return $this->redirection();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $model = $this->model->findOrFail($id);
        ${camel_case($this->base)} = $model;

        $this->title        = $this->getTrans('editdata', ['data' => title_case($this->getTrans($this->base))]);
        $this->description  = $this->getTrans('editingdata', ['data' => $this->getTrans($this->base)]);
        $this->breadcrumb3  = $this->getTrans('edit');
        $this->action       = 'update';
        $this->params       = array_merge($request->query() ? $request->query() : [], compact('id'));
        
        $this->view->share();
        
        $this->loadFormClasses($model);

        return view('livecms::'.$this->groupName.'.'.camel_case($this->base).'.form', compact(camel_case($this->base)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->model = $this->model->findOrFail($id);

        $request = $this->processRequest($request);

        if ($request === true) {
            return $this->redirection();
        }

        $this->validate($request, $this->model->rules());

        $this->model->update($request->all());

        $saved = $this->afterSaving($request);

        if ($saved) {
            return $this->redirection();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->model = $this->model->findOrFail($id);

        $deleted = $this->model->delete();

        $title = ucfirst(trans('livecms::livecms.destroysuccess'));
        $message = ucfirst(trans('livecms::livecms.destroysuccessmessage', ['model' => trans('livecms::livecms.'.$this->base)]));
        alert()->success($message, $title);

        if ($deleted) {
            return $this->redirection();
        }
    }
}
