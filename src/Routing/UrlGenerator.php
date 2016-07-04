<?php

namespace LiveCMS\Routing;

use Illuminate\Support\Str;
use Mrofi\LaravelSharedHostingPackage\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool|null  $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        // First we will check if the URL is already a valid URL. If it is we will not
        // try to generate a new one but will simply return the URL as is, which is
        // convenient since developers do not always have to check if it's valid.
        if ($this->isValidUrl($path)) {
            return $path;
        }

        $subfolder = site()->subfolder;
        $path = $subfolder ? $subfolder.'/'.Str::replaceFirst('/'.$subfolder.'/', '', $path) : $path;
        return parent::to($path, $extra, $secure);
    }
}
