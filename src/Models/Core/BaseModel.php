<?php

namespace LiveCMS\Models\Core;

use Illuminate\Database\Eloquent\Model;
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

    /* ATTRIBUTES */
    protected $defWidth = 480;// landscape
    protected $defHeight = 360; // portrait
    protected $extendedThumbnailStyle = [];
    protected $thumbnailStyle = [
        'small_square' => '128x128',
        'medium_square' => '256x256',
        'large_square' => '512x512',
        'xlarge_square' => '2048x2048',
        'small_cover' => '240x_',
        'normal_cover' => '360x_',
        'medium_cover' => '480x_',
        'large_cover' => '1280x_',
        'small_banner' => '_x240',
        'normal_banner' => '_x360',
        'medium_banner' => '_x480',
        'large_banner' => '_x1280'
    ];
    protected $defThumbnailName = '_thumbnail';
    protected $baseFolder = 'public/files';
    protected $images = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $className = strtolower(class_basename(static::class));

        $this->baseFolder = $this->baseFolder.'/'.$className;

        static::setPolicy(AdminPolicy::class);
    }
}
