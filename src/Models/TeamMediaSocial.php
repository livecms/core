<?php

namespace LiveCMS\Models;

use Illuminate\Database\Eloquent\Model;
use LiveCMS\Models\Traits\AdminModelTrait;

class TeamMediaSocial extends Model
{
    use AdminModelTrait;

    protected $fillable = ['social', 'url'];

    protected $socials = ['facebook', 'twitter', 'instagram', 'google-plus', 'linkedin', 'github'];

    public function socials()
    {
        return $this->socials;
    }
}
