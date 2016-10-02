<?php

namespace LiveCMS\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use LiveCMS\Models\Traits\BaseModelTrait;
use LiveCMS\Models\Traits\ImagableTrait;
use LiveCMS\Models\Traits\ModelAuthorizationTrait;
use LiveCMS\Models\Contracts\BaseModelInterface as BaseModelContract;
use LiveCMS\Models\Contracts\ModelAuthorizationInterface as ModelAuthorizationContract;
use LiveCMS\Policies\AdminPolicy;
use LiveCMS\Support\Uploader\Contracts\ModelUploaderInterface as ModelUploaderContract;
use LiveCMS\Support\Thumbnailer\Contracts\ModelThumbnailerInterface as ModelThumbnailerContract;

abstract class BaseModel extends Model implements BaseModelContract, ModelAuthorizationContract, ModelUploaderContract, ModelThumbnailerContract
{
    use ImagableTrait, BaseModelTrait, ModelAuthorizationTrait;

    protected $allSites = false;

    protected $selfPost = false;

    protected $useAuthorization = true;

    protected $hidden = ['site_id'];

    protected $dependencies = [];

    protected $rules = [];

    protected $aliases = [];

    protected $addition = [];

    protected $deletion = [];

    protected $forms = [];

    protected $thumbnailStyle = [];

    protected $baseFolder = null;

    protected $images = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->thumbnailStyle = Config::get('livecms.thumbnailer.thumbnailStyle', []);

        $className = strtolower(class_basename(static::class));

        $baseFolder = Config::get('livecms.uploader.baseFolder');

        $this->baseFolder = $baseFolder.'/'.$className;

        static::setPolicy(AdminPolicy::class);
    }
}
