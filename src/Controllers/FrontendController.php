<?php

namespace LiveCMS\Controllers;

use Illuminate\Http\Request;

class FrontendController extends BaseController
{
    public function __construct()
    {
        $helpers = theme('front', 'partials.helpers', true).'.php';
        $variables = file_exists($helpers) ? require_once $helpers : [];
        view()->share($variables);
    }
}
