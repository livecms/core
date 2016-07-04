<?php

use LiveCMS\Models\GenericSetting as Setting;
use LiveCMS\Models\Site;
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

    function getSlug($name)
    {
        return globalParams('slug_'.$name, config('livecms.slugs.'.$name, $name));
    }
}

if (! function_exists('getMenus')) {

    function getMenus($prefixSlug, array $menus = [])
    {
        $view = '';
        $subfolderPrefix = site()->subfolder;
        $subfolderPrefix = $subfolderPrefix ? $subfolderPrefix.'.' : $subfolderPrefix;

        foreach ($menus as $menu) {

            if (is_array($uri = $menu['uri'])) {

                $activeMenu = false;
                $canReadMenu = false;
                foreach (collect($uri)->pluck('uri')->toArray() as $uri) {
                    $slug = getSlug($uri);
                    $activeMenu = $activeMenu || isInCurrentRoute($subfolderPrefix.$prefixSlug.'.'.$slug.'.');
                    $canReadMenu = $canReadMenu || canRead($subfolderPrefix.$prefixSlug.'.'.$slug.'.index');
                }

                if ($canReadMenu) {

                    $view .= '<li class="'.($activeMenu ? 'active' : '').' treeview">
                        <a href="#"><i class="fa fa-'.$menu['icon'].'"></i> <span>'.trans('livecms::livecms.'.$menu['title']).'</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">';

                    foreach ($menu['uri'] as $subMenu) {

                        if (canRead($menuUrl = ($menuLink = $subfolderPrefix.$prefixSlug.'.'.getSlug($subMenu['uri']).'.').'index')) {

                            $view .= '<li class="'. (isInCurrentRoute($menuLink) ? 'active' : '').'"><a href="'. route($menuUrl) .'"><i class="fa fa-'.$subMenu['icon'].'"></i> <span>'.trans('livecms::livecms.'.$subMenu['title']).'</span></a></li>';
                        }
                    }

                    $view .= '</ul> ';
                }

            } else {
                
                if (canRead($menuUrl = ($menuLink = $subfolderPrefix.$prefixSlug.'.'.getSlug($menu['uri']).'.').'index')) {

                    $view .= '<li class="'.(isInCurrentRoute($menuLink) ? 'active' : '').'"><a href="'. route($menuUrl) .'"><i class="fa fa-'.$menu['icon'].'"></i> <span>'.trans('livecms::livecms.'.$menu['title']).'</span></a></li>';
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
        $host       = $site->getHost();
        $path       = $site->getPath();
        $domain     = $site->getDomain();
        $baseUrl    = $site->getBaseUrl();
        $subDomain  = $site->subdomain;
        $subFolder  = $site->subfolder;

        // ROUTING        
        $router->group(
            ['domain' => $host, 'middleware' => 'web', 'prefix' => $subFolder],
            function ($router) use ($adminSlug, $subDomain, $subFolder, $callback) {
                $callback($router, $adminSlug, $subDomain, $subFolder);
            }
        );
    }
}

if (! function_exists('frontendRoute')) {

    function frontendRoute($router)
    {
        $router->group(['namespace' => 'Frontend'], function ($router) {
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

        if (view()->exists($view = $type.$location)) {

            if ($getPath) {
                return $viewPath.DIRECTORY_SEPARATOR.(str_replace('.', DIRECTORY_SEPARATOR, $view));
            }

            return $view;
        }

        if ($getPath) {
            $view = str_replace(['::', '.'], DIRECTORY_SEPARATOR, $types.$location);
            return $viewPath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.$view;
        }

        return $types.$location;
    }
}

if (! function_exists('get')) {

    function get($postType, $identifier = null, $number = 1, array $where = [], array $fields = ['*'], $order = 'asc', $orderBy = 'id')
    {
        $namespace = 'App\\Models\\';

        $class = $namespace.studly_case(snakeToStr($postType));

        $instance = app($class);


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

        $ids = app($class)->whereHas('categories', function ($query) use ($category) {
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

        $ids = app($class)->whereHas('tags', function ($query) use ($tag) {
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

    function dataImplode($data, $attribute)
    {
        return rtrim($data->pluck($attribute)->implode(', '), ', ');
    }
}
