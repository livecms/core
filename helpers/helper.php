<?php

const LIVECMS = 'livecms';

if (! function_exists('LC_CurrentInstance')) {
    function LC_CurrentInstance()
    {
        return config('livecms.current');
    }
}

if (! function_exists('LC_CurrentConfig')) {
    function LC_CurrentConfig($key)
    {
        if ($currentConfig = config('livecms.current_config.'.$key)) {
            return $currentConfig;
        }

        $currentConfig = config('livecms.instances.'.LC_CurrentInstance());
        config(['livecms.current_config' => $currentConfig]);
        return $currentConfig[$key];
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