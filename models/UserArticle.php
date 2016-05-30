<?php

namespace App\Models;

use LiveCMS\Models\Contracts\UserOnlyInterface as UserContract;

class UserArticle extends Article implements UserContract
{
    protected $table = 'articles';

    public function allowsUserRead($user)
    {
        return true;
    }
}
