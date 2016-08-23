<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Contracts\UserOnlyInterface as UserContract;

class UserArticle extends Article implements UserContract
{
    protected $table = 'articles';

    public function allowsUserRead($user)
    {
        return true;
    }
}
