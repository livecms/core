<?php

use LiveCMS\Models\Core\GenericSetting as Setting;
use LiveCMS\Models\Core\Site;
use LiveCMS\Models\Core\PostableModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

if (! function_exists('globalParams')) {

    function globalParams($key = null, $default = false)
    {
        $params = [];
        try {
            
            $params = Cache::rememberForever('global_params', function () {
                if (!Schema::hasTable('settings')) {
                    return collect();
                };
         
                return Setting::get();
            });
            $params = $params->groupBy('site_id')->toArray();
            
        } catch (\Exception $e) {
            
        }
        
        $siteId = site()->id;
        $params = isset($params[$siteId]) ? collect($params[$siteId])->pluck('value', 'key') : [];

        if ($key == null) {
            return $params;
        }
        
        return isset($params[$key]) ? $params[$key] : $default;
    }
}

if (! function_exists('getSlug')) {

    function getSlug($name, $package = 'livecms')
    {
        return globalParams('slug'.($package == 'livecms' ? '' : $package).'_'.$name, config($package.'.routing.slugs.'.$name, $name));
    }
}

if (! function_exists('getMenus')) {

    function getMenus($prefixSlug, array $menus = [], $package = 'livecms')
    {
        $view = '';
        $subfolderPrefix = site()->subfolder;
        $subfolderPrefix = $subfolderPrefix ? $subfolderPrefix.'.' : $subfolderPrefix;

        foreach ($menus as $menu) {

            if (is_array($uri = $menu['uri'])) {

                $activeMenu = false;
                $canReadMenu = false;
                foreach (collect($uri)->pluck('uri')->toArray() as $uri) {
                    $slug = getSlug($uri, $package);
                    $activeMenu = $activeMenu || isInCurrentRoute($subfolderPrefix.$prefixSlug.'.'.$slug.'.');
                    $canReadMenu = $canReadMenu || canRead($subfolderPrefix.$prefixSlug.'.'.$slug.'.index');
                }

                if ($canReadMenu) {

                    $view .= '<li class="'.($activeMenu ? 'active' : '').' treeview">
                        <a href="#"><i class="fa fa-'.$menu['icon'].'"></i> <span>'.trans('livecms::'.$package.'.'.$menu['title']).'</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">';

                    foreach ($menu['uri'] as $subMenu) {

                        if (canRead($menuUrl = ($menuLink = $subfolderPrefix.$prefixSlug.'.'.getSlug($subMenu['uri'], $package).'.').'index')) {

                            $view .= '<li class="'. (isInCurrentRoute($menuLink) ? 'active' : '').'"><a href="'. route($menuUrl) .'"><i class="fa fa-'.$subMenu['icon'].'"></i> <span>'.trans('livecms::'.$package.'.'.$subMenu['title']).'</span></a></li>';
                        }
                    }

                    $view .= '</ul> ';
                }

            } else {
                if (canRead($menuUrl = ($menuLink = $subfolderPrefix.$prefixSlug.'.'.getSlug($menu['uri'], $package).'.').'index')) {

                    $view .= '<li class="'.(isInCurrentRoute($menuLink) ? 'active' : '').'"><a href="'. route($menuUrl) .'"><i class="fa fa-'.$menu['icon'].'"></i> <span>'.trans('livecms::'.$package.'.'.$menu['title']).'</span></a></li>';
                }
            }
        }

        return $view;
    }
}

if (! function_exists('isInCurrentRoute')) {

    function isInCurrentRoute($part)
    {
        return starts_with(request()->route()->getName(), $part);
    }
}

if (! function_exists('canRead')) {

    function canRead($routeName)
    {
        $route = Route::getRoutes()->getByName($routeName);
        
        if ($route == null) {
            return null;
        }

        $action = $route->getAction();

        list($controller, $action) = explode('@', $action['controller']);

        return app($controller)->getControllerModel()->allowsUserRead(auth()->user());
    }
}

if (! function_exists('snakeToStr')) {
    
    function snakeToStr($snake)
    {
        return implode(' ', explode('_', $snake));
    }
}

if (! function_exists('site')) {

    function site()
    {
        return app(Site::class)->getCurrent();
    }
}

if (! function_exists('liveCMSRouter')) {

    function liveCMSRouter($router, callable $callback)
    {
        $adminSlug  = getSlug('admin');
        $site       = site()->getCurrent();
        $subDomain  = $site->subdomain;
        $subFolder  = $site->subfolder;

        // ROUTING        
        $router->group(
            ['middleware' => 'web', 'prefix' => $subFolder],
            function ($router) use ($adminSlug, $subDomain, $subFolder, $callback) {
                $callback($router, $adminSlug, $subDomain, $subFolder);
            }
        );
    }
}

if (! function_exists('frontendRoute')) {

    function frontendRoute($router)
    {
        $router->group(['namespace' => 'LiveCMS'], function ($router) {
            $router->get('/', ['as' => 'home', 'uses' => 'PageController@home']);
            $router->get('{arg0?}/{arg1?}/{arg2?}/{arg3?}/{arg4?}/{arg5?}', 'PageController@routes');
        });
    }
}

if (! function_exists('theme')) {

    function theme($type, $location = 'template', $getPath = false)
    {
        $types = 'livecms::themes.'.config('livecms.themes.'.$type);
        $location = '.'.$location;
        $viewPath = config('view.paths.0');

        if (view()->exists($view = $types.$location)) {
            if ($getPath) {
                return view($view)->getPath();
            }
            return $view;
        }
    }
}

if (! function_exists('get')) {

    function get($postType, $identifier = null, $number = 1, array $where = [], array $fields = ['*'], $order = 'asc', $orderBy = 'id')
    {
        $namespace = 'App\\Models\\';

        $class = $namespace.studly_case(snakeToStr($postType));

        if (!class_exists($class)) {
            $class = 'LiveCMS\\Models\\'.studly_case(snakeToStr($postType));
        }

        $instance = app($class);

        $where['status'] = isset($where['status']) ? $where['status'] : PostableModel::STATUS_PUBLISHED;

        if ($identifier === null) {

            return $instance->where($where)->take($number)->orderBy($orderBy, $order)->get($fields);
        }

        $show = is_array($identifier) ? $instance->whereIn($instance->getKeyName(), $identifier)->take($number)->orderBy($orderBy, $order)->get($fields) : $instance->find($identifier);

        if (!$show) {
            $show = $instance->where('slug', $identifier)->first($fields);
        }

        if ($show instanceof LiveCMS\Models\PostableModel) {
            return $show->getContent();
        }

        if ($show instanceof Collection) {
            return $show;
        }

        return null;
    }
}

if (! function_exists('getCategory')) {

    function getCategory($category, $postType = 'article', $number = 10, array $fields = ['*'], $order = 'asc', $orderBy = 'id')
    {
        $namespace = 'App\\Models\\';

        $class = $namespace.studly_case(snakeToStr($postType));

        if (!class_exists($class)) {
            $class = 'LiveCMS\\Models\\'.studly_case(snakeToStr($postType));
        }

        $ids = app($class)->where('status', PostableModel::STATUS_PUBLISHED)->whereHas('categories', function ($query) use ($category) {
            $query->where(function ($query) use ($category) {
                $table = $query->getModel()->getTable();
                $query->where($table.'.category', $category)->orWhere($table.'.slug', $category);
            });
        })->pluck('id')->toArray();

        return get($postType, $ids, $number, [], $fields, $order, $orderBy);
    }
}

if (! function_exists('getTag')) {

    function getTag($tag, $postType = 'article', $number = 10, array $fields = ['*'], $order = 'asc', $orderBy = 'id')
    {
        $namespace = 'App\\Models\\';

        $class = $namespace.studly_case(snakeToStr($postType));

        if (!class_exists($class)) {
            $class = 'LiveCMS\\Models\\'.studly_case(snakeToStr($postType));
        }

        $ids = app($class)->where('status', PostableModel::STATUS_PUBLISHED)->whereHas('tags', function ($query) use ($tag) {
            $query->where(function ($query) use ($tag) {
                $table = $query->getModel()->getTable();
                $query->where($table.'.tag', $tag)->orWhere($table.'.slug', $tag);
            });
        })->pluck('id')->toArray();

        return get($postType, $ids, $number, [], $fields, $order, $orderBy);
    }
}

if (! function_exists('child')) {

    function child($post, $index = 0, $attribute = 'content')
    {
        if (($children = $post->children) == null || count($children) == 0) {
            
            return null;
        }

        if (is_numeric($index) && $index < count($children)) {

            $child = $children[$index] ? $children[$index] : $children[count($children) - 1];

        } else {

            $child = $children->where('slug', $index)->first();
        }

        return $child ? $child->$attribute : null;
    }
}

if (! function_exists('dataImplode')) {

    function dataImplode($data, $attribute, $callback = null, $keyBy = null)
    {
        $data = $data->pluck($attribute, $keyBy);
        if (is_callable($callback)) {
            $data = $data->map($callback);
        }
        return rtrim($data->implode(', '), ', ');
    }
}

if (! function_exists('disqusComment')) {

    function disqusComment($artile, $disqusId = null, $url = null, $title = null, $identifier = null) {
        return '
            <div id="disqus_thread"></div>
            <script>
                var disqus_config = function () {
                    this.page.url = \''.( $url ?: request()->url() ).'\';
                    this.page.identifier = \''.( $identifier ?: $artile->id ).'\';
                    this.page.title = \''.( $title ?: $artile->title ).'\';
                };

                (function() {  // REQUIRED CONFIGURATION VARIABLE: EDIT THE SHORTNAME BELOW
                    var d = document, s = d.createElement(\'script\');

                    s.src = \'//'.( $disqusId ?: globalParams('disqus_id') ).'.disqus.com/embed.js\';

                    s.setAttribute(\'data-timestamp\', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        ';
    }
}
