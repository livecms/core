<?php

namespace LiveCMS\Models;

use LiveCMS\Models\Core\BaseModel;
use LiveCMS\Models\Core\Profile;
use LiveCMS\Models\Core\Setting;
use LiveCMS\Models\Traits\AdminModelTrait;

class Contact extends BaseModel
{
    use AdminModelTrait;

    protected $fillable = ['address', 'address2', 'city', 'country', 'postcode', 'telephone', 'faximile', 'email', 'socials'];

    protected $forms = [
        'text' => ['address', 'address2', 'city', 'country', 'postcode', 'telephone', 'faximile', 'email'],
    ];

    protected $casts = [
        'socials' => 'array',
    ];

    public function rules()
    {
        return [
            'postcode' => 'numeric',
            'email' => 'email',
            'socials.*' => 'active_url',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
     
        $default = Setting::privateOnly()->pluck('value', 'key')->toArray();

        $attributes = array_replace($default, $attributes);

        $this->fill($attributes);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $site_id = site()->id;

        $contact = new static;

        $fillable = $contact->getFillable();

        foreach (array_only($attributes, $fillable) as $key => $value) {

            if  (is_array($value)) {
                $value = json_encode($value);
            }
            $row = Setting::privateOnly()->firstOrNew(compact('key', 'site_id'));
            $row->fill(compact('value'));
            $row->save();
        }

        return true;
    }

    public function socialMedias()
    {
        return (new Profile)->socialMedias();
    }

    public function getSocials($social = null)
    {
        $socials = json_decode($this->socials, true);

        if (count($socials)) {
            
            if ($social === null) {
                return $socials;
            }

            foreach ($socials as $key => $value) {
                if ($social == $key) {

                    return $value;
                }
            }
        }

        return null;
    }
}
