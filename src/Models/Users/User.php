<?php

namespace LiveCMS\Models\Users;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LiveCMS\Models\Traits\UserModelTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LiveCMS\Models\Contracts\UserModelInterface as UserModelContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    UserModelContract
{
    use Authenticatable, Authorizable, CanResetPassword, UserModelTrait;

    protected $withSuper = true;

    protected static $picturePath = 'users';
}
