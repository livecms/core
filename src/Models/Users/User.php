<?php

namespace LiveCMS\Models\Users;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Config;
use LiveCMS\Models\Traits\UserModelTrait;
use LiveCMS\Models\Traits\ImagableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use LiveCMS\Models\Contracts\UserModelInterface as UserModelContract;
use LiveCMS\Support\Uploader\Contracts\ModelUploaderInterface as ModelUploaderContract;
use LiveCMS\Support\Thumbnailer\Contracts\ModelThumbnailerInterface as ModelThumbnailerContract;


class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    UserModelContract,
    ModelUploaderContract,
    ModelThumbnailerContract
{
    use Authenticatable, Authorizable, CanResetPassword, UserModelTrait, ImagableTrait, Notifiable;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $withSuper = true;

    protected static $picturePath = 'users';

    protected $thumbnailStyle = [];

    protected $baseFolder = null;

    protected $images = ['avatar', 'background'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->thumbnailStyle = Config::get('livecms.thumbnailer.thumbnailStyle', []);

        $className = strtolower(class_basename(static::class));

        $baseFolder = Config::get('livecms.uploader.baseFolder');

        $this->baseFolder = $baseFolder.'/'.$className;
    }
}
