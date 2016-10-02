<?php

namespace LiveCMS\Models\Traits;

use ImageMax;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use LiveCMS\Support\Thumbnailer\ModelThumbnailerTrait;
use LiveCMS\Support\Uploader\ModelUploaderTrait;

trait ImagableTrait
{
    use ModelThumbnailerTrait {
        toArray as thumbnailerToArray;
        getAttribute as thumbnailerGetAttribute;
    }

    use ModelUploaderTrait;

    protected function getImagableAttributes()
    {
        return property_exists($this, 'images') ? (array) $this->images : $this->imageAttributes;
    }

    protected function isUseImageMax()
    {
        return Config::get('livecms.useimagemax', false);
    }

    public function toArray()
    {
        if ($this->isUseImageMax()) {
            $array = parent::toArray();
            return $this->getImagesArray($array);
        }
        return $this->thumbnailerToArray();
    }

    public function getAttribute($key)
    {
        if ($this->isUseImageMax()) {
            $attribute = parent::getAttribute($key);
            
            if (!$attribute) {

                $profiles = config('imagemax.profiles', []);
                
                foreach ($profiles as $profile => $options) {
                
                    if (Str::endsWith($key, $last = '_'.str_slug($profile, '_')))
                    {
                        $image = Str::replaceLast($last, '', $key);

                        if (in_array($image, $this->getImagableAttributes()) && $this->getAttribute($image)) {

                            return ImageMax::make($this->getAttribute($image), $options);
                        }
                    }
                }
            }
            return $attribute;
        }
        return $this->thumbnailerGetAttribute($key);
    }

    protected function getImagesArray($attributes)
    {
        $images = $this->getImagableAttributes();

        $profiles = config('imagemax.profiles', []);

        foreach ($images as $image) {

            foreach ($profiles as $profile => $options) {
                
                if (isset($attributes[$image])) {

                    $attributes[str_slug($image.'_'.$profile, '_')] = ImageMax::make($attributes[$image], $options);
                }
            }
        }

        return $attributes;
    }
}