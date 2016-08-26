<?php

namespace LiveCMS\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const SUPER = 'super';
    const ADMIN = 'admin';
    const AUTHOR = 'author';
    const BANNED = 'banned';
    const REGISTERED = 'registered';

    protected $fillable = ['role'];

    public function getRoles()
    {
        return [
            static::SUPER => title_case(static::SUPER),
            static::ADMIN => title_case(static::ADMIN),
            static::AUTHOR => title_case(static::AUTHOR),
            static::REGISTERED => title_case(static::REGISTERED),
        ];
    }
}
