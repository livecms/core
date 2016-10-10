<?php

namespace LiveCMS\Controllers\Backend;

use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LiveCMS\Models\Core\Permalink;
use LiveCMS\Models\Article as Model;
use LiveCMS\Models\Category;
use LiveCMS\Models\Tag;

class ArticleController extends PostableController
{
    protected $category;
    protected $tag;
    protected $permalink;
    protected $stackedFields = ['fa-star'];

    public function __construct(Model $model, Category $category, Tag $tag, $base = 'article')
    {
        parent::__construct($model, $base);

        $this->unsortables = array_merge($this->unsortables, ['tag', 'category']);

        $this->category = $category;
        $this->tag = $tag;

        $this->formLeftWidth = 2;
        $this->breadcrumb2Icon  = 'files-o';
        
        $this->view->share();
    }

    protected function processRequest($request)
    {
        if (!$request->get('is_featured')) {
            $request->merge(['is_featured' => false]);
        }
        return parent::processRequest($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function putUpdateFeatured(Request $request, $id)
    {
        $this->model = $this->model->findOrFail($id);

        $request = $this->processRequest($request);

        if ($request === true) {
            return $this->redirection();
        }

        $is_featured = $request->get('is_featured');
        $this->model->update(compact('is_featured'));

        $saved = $this->afterSaving($request);

        if ($saved) {
            return $this->redirection();
        }
    }

    protected function processDatatables($datatables)
    {
        $datatables = parent::processDatatables($datatables);
        
        $datatables = $datatables
            ->addColumn('category', function ($data) {
                return dataImplode($data->categories, 'category');
            })
            ->addColumn('tag', function ($data) {
                return dataImplode($data->tags, 'tag');
            });

        if (Str::endsWith(get_called_class(), 'Backend\ArticleController')) {
            return $datatables->editColumn('is_featured', function ($data) {
                return $data->is_featured ? 
                    (Form::open(['style' => 'display: inline!important', 'method' => 'put',
                        'action' => [$this->baseClass.'@putUpdateFeatured', $data->{$this->model->getKeyName()}]
                    ]).
                    '  <button type="submit" name="is_featured" value="0" onClick="return confirm(\''.$this->getTrans('unsetfeaturedconfirmation').'\');" 
                        class="btn btn-small btn-link" title="'.$this->getTrans('unsetfeatured').'">
                            <i class="fa fa-xs fa-star text-yellow"></i> 
                    </button>
                    </form>')
                 :
                    (Form::open(['style' => 'display: inline!important', 'method' => 'put',
                        'action' => [$this->baseClass.'@putUpdateFeatured', $data->{$this->model->getKeyName()}]
                    ]).
                    '  <button type="submit" name="is_featured" value="1" onClick="return confirm(\''.$this->getTrans('setfeaturedconfirmation').'\');" 
                        class="btn btn-small btn-link" title="'.$this->getTrans('setfeatured').'">
                            <i class="fa fa-xs fa-star-o"></i> 
                    </button>
                    </form>');
            });
        }
        return $datatables;
    }

    protected function loadFormClasses($model)
    {
        $this->categories   = $this->category->pluck('category', 'id')->toArray();
        $this->tags         = $this->tag->pluck('tag', 'id')->toArray();
        
        parent::loadFormClasses($model);
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

        $tags = $request->get('tags', []);

        $newTags = [];

        foreach ($tags as $index => $tag) {
            
            if (is_numeric($tag) && $this->tag->find($tag)) {
                continue;
            }

            $tag = $this->tag->firstOrNew(['tag' => $tag]);

            if (!$tag->id) {

                $i = 0;
                do {
                    $slug = str_slug($tag->tag).($i++ > 0 ? '-'.$i : '');
                } while ($this->tag->where('slug', $slug)->first());
                
                $tag->slug = $slug;
                $tag->save();
            }

            $newTags[$index] = $tag->id;
        }

        $tags = array_replace($tags, $newTags);

        $request->merge(compact('categories', 'tags'));

        $this->model->categories()->sync($request->get('categories', []));
        $this->model->tags()->sync($request->get('tags', []));

        return parent::afterSaving($request);
    }
}
