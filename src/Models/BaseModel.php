<?php

namespace LiveCMS\Models;

use Illuminate\Database\Eloquent\Model;
use LiveCMS\Models\Traits\BaseModelTrait;
use LiveCMS\Models\Traits\ModelAuthorizationTrait;
use LiveCMS\Models\Contracts\BaseModelInterface as BaseModelContract;
use LiveCMS\Models\Contracts\ModelAuthorizationInterface as ModelAuthorizationContract;
use LiveCMS\Policies\AdminPolicy;

abstract class BaseModel extends Model implements BaseModelContract, ModelAuthorizationContract
{
    use BaseModelTrait, ModelAuthorizationTrait;

    protected $allSites = false;

    protected $selfPost = false;

    protected $useAuthorization = true;

    protected $hidden = ['site_id'];

    protected $dependencies = [];

    protected $rules = [];

    protected $aliases = [];

    protected $addition = [];

    protected $deletion = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        static::setPolicy(AdminPolicy::class);
    }
}
