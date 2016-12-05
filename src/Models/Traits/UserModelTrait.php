<?php

namespace LiveCMS\Models\Traits;

use LiveCMS\Models\Core\Site;
use LiveCMS\Models\Users\Role;

trait UserModelTrait
{
    public function withSuper($bool = true)
    {
        $this->withSuper = $bool;
        return $this;
    }

    public function newQuery()
    {
        $query = parent::newQuery();

        if ($this->withSuper) {
            return $query;
        }

        return $query->whereHas('roles', function ($query) {
            $query->where('role', '<>', Role::SUPER);
        });
    }

    public function getInitial()
    {
        $name = strtoupper($this->name);

        $words = count($names = explode(' ', $name));

        $inits = array_map(function ($value) {
            return substr($value, 0, 1);
        }, $names);

        $initials = $inits[0]. ($words > 1 ?  last($inits) : '');

        return $initials;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeAdminOnly($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('role', Role::ADMIN);
        });
    }

    public function getSiteRootUrl()
    {
        return $this->site ? $this->site->getRootUrl() : site()->getRootUrl();
    }

    public function getIsSuperAttribute()
    {
        return $this->roles->where('role', Role::SUPER)->count() > 0;
    }

    public function getIsAdminAttribute()
    {
        return $this->roles->where('role', Role::ADMIN)->count() > 0;
    }

    public function getIsAuthorAttribute()
    {
        return $this->roles->where('role', Role::AUTHOR)->count() > 0;
    }

    public function getIsLimitedAttribute()
    {
        return $this->roles->where('role', 'user')->count() > 0;
    }

    public function getIsAdministerAttribute()
    {
        $roles = [Role::SUPER, Role::ADMIN];

        return $this->roles->filter(function ($item) use ($roles) {
            return in_array(data_get($item, 'role'), $roles);
        })->count() > 0;
    }

    public function getIsBannedAttribute()
    {
        return $this->roles->where('role', Role::BANNED)->count() > 0;
    }

    public function allowsUserRead($user)
    {
        return $user->is_admin;
    }

    public function getPicturePath()
    {
        return static::$picturePath;
    }

    public function getAvatarAttribute($avatar)
    {
        return $avatar ? asset($this->getPicturePath().'/'.$avatar) : null;
    }

    public function getBackgroundAttribute($background)
    {
        return $background ? asset($this->getPicturePath().'/'.$background) : null;
    }

}
