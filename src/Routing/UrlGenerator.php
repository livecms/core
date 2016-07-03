<?php

namespace LiveCMS\Routing;

use Mrofi\LaravelSharedHostingPackage\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the base URL for the request.
     *
     * @param  string  $scheme
     * @param  string  $root
     * @return string
     */
    protected function getRootUrl($scheme, $root = null)
    {
        if ($root == null) {
            $root = $this->request->root();
        }

        $root = trim($root, '/').'/'.site()->subfolder;

        return parent::getRootUrl($scheme, $root);
    }
}
