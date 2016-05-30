<?php

namespace LiveCMS\Routing;

use Illuminate\Routing\Redirector as BaseRedirector;

class Redirector extends BaseRedirector
{
    /**
     * Create a new Redirector instance.
     *
     * @param  \LiveCMS\Routing\UrlGenerator  $generator
     * @return void
     */
    public function __construct(UrlGenerator $generator)
    {
        parent::__construct($generator);
    }
}
