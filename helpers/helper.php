<?php

if (! defined('LIVECMS')) {
    define('LIVECMS', 'livecms');
}

if (! function_exists('LC_CurrentInstance')) {
    function LC_CurrentInstance()
    {
        return config('livecms.current');
    }
}

if (! function_exists('LC_CurrentConfig')) {
    function LC_CurrentConfig($key, $default = null)
    {
        if ($currentConfig = config('livecms.current_config.'.$key, $default)) {
            return $currentConfig;
        }

        $currentConfig = config('livecms.instances.'.LC_CurrentInstance());
        config(['livecms.current_config' => $currentConfig]);
        return $currentConfig[$key] ?? $default;
    }
}

if (! function_exists('LC_Guard')) {
    function LC_Guard()
    {
        $instance = LC_CurrentInstance();
        if ($guard = config('livecms.instances.'.$instance.'.guard')) {
            return $guard;
        }
        return config('livecms.guard');
    }
}

if (! function_exists('LC_GuardName')) {
    function LC_GuardName()
    {
        $guard = LC_Guard();
        return is_string($guard) ? $guard : $guard['name'];
    }
}

if (! function_exists('LC_Middleware')) {
    function LC_Middleware($group, $instance = null)
    {
        $middlewares = [];
        $wrapped = config('livecms.middleware.wrapped.livecms.'.$group, []);
        foreach ($wrapped as $key => $middleware) {
            if (array_last(explode('.', $middleware)) == (string) $key) {
                $middlewares[] = $middleware.($instance ? ':'.$instance : '');
            } else {
                $middlewares[] = $middleware;
            }
        }

        if ($instance) {
            $wrapped = config('livecms.middleware.wrapped.'.$instance.'.'.$group, []);
            foreach ($wrapped as $middleware) {
                $middlewares[] = $middleware;
            }
        }

        return $middlewares;
    }
}

if (! function_exists('LC_BaseRoute')) {
    function LC_BaseRoute()
    {
        return config('livecms.base_route');
    }
}

if (! function_exists('LC_Route')) {
    function LC_Route($routeName)
    {
        return route(LC_BaseRoute().'.'.$routeName);
    }
}

if (! function_exists('LC_Session')) {
    function LC_Session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        $key = LC_GuardName().'_sessions';

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (! function_exists('LC_DestroySession')) {
    function LC_DestroySession()
    {
        $key = LC_GuardName().'_sessions';
        return app('session')->forget($key);
    }
}


if (! function_exists('LC_CurrentTheme')) {
    function LC_CurrentTheme()
    {
        if ($currentTheme = config('livecms.current_theme')) {
            return $currentTheme;
        }

        $currentTheme = LC_CurrentConfig('theme');
        config(['livecms.current_theme' => $currentTheme]);
        return $currentTheme;
    }
}


if (! function_exists('LC_Asset')) {
    function LC_Asset($path = null)
    {
        return asset('vendor/livecms/'.LC_CurrentTheme().'/'.$path);
    }
}

if (! function_exists('LC_GetTitle')) {
    function LC_GetTitle()
    {
        return 'LiveCMS';
    }
}