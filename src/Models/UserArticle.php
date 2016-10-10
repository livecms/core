<?php

namespace LiveCMS\Models;

use Illuminate\Support\Facades\Config;
use LiveCMS\Models\Contracts\UserOnlyInterface as UserContract;

class UserArticle extends Article implements UserContract
{
    protected $table = 'articles';

    protected $excepts = ['is_featured'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $baseFolder = Config::get('livecms.uploader.baseFolder');
        $this->baseFolder = $baseFolder.'/article';
    }

    public function allowsUserRead($user)
    {
        return true;
    }
}
