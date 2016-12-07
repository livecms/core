<?php

namespace LiveCMS\Models\Traits;

use Laravel\Scout\Searchable;
use LiveCMS\Models\Core\PostableModel;

trait SearchableTrait
{
    use Searchable {
        search as LaravelSearch;
    }

    /**
     * Override Laravel Scout's search.
     *
     * @param  string  $query
     * @param  Closure  $callback
     * @return \Laravel\Scout\Builder
     */
    public static function search($query, $callback = null)
    {
        if (config('livecms.deepsearch', false)) {
            return static::LaravelSearch($query, $callback);
        }

        $instance = new static;
        if ($instance instanceof PostableModel) {
            $mandatoryField = 'title';
            $selectFields = ['id', 'title', 'slug', 'published_at', 'status', 'author_id'];
            return $instance->select($selectFields)->where($mandatoryField, 'like', '%'.$query.'%');
        } else {
            $mandatoryField = strtolower((new \ReflectionClass($instance))->getShortName());
            if (in_array($mandatoryField, $instance->getFillable())) {
                return $instance->where($mandatoryField, 'like', '%'.$query.'%');
            }
        }


        return $instance->where($instance->getKeyName(), null);
    }
}
