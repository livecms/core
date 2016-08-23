<?php

namespace LiveCMS\Models\Core;

use LiveCMS\Models\Traits\AdminModelTrait;

class Permalink extends BaseModel
{
    use AdminModelTrait;

    protected $fillable = ['permalink', 'postable_type', 'postable_id'];

    protected $appends = ['type'];

    public function rules()
    {
        $uri = explode('/', request()->get('permalink'));
        $uri = array_splice($uri, 0, 5);
        $permalink = implode('/', array_map('str_slug', $uri));

        request()->merge(compact('permalink'));

        return [
            'permalink' => 'required|unique:'.$this->getTable().',permalink'.(($this->id != null) ? ','.$this->id : ',NULL').','.$this->getKeyName().',site_id,'.$this->site_id,
        ];
    }

    public function postable()
    {
        return $this->morphTo();
    }

    public function getTypeAttribute()
    {
        return basename(str_replace('\\', '/', $this->postable_type));
    }
}
