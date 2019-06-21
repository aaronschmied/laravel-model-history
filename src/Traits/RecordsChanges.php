<?php
/**
 * Copyright: © 2019 Pro Sales AG
 * Author: Aaron Schmied <aaron@pro-sales.ch>
 * Date: 2019-06-20
 * Time: 17:25
 */

namespace AaronSchmied\ModelHistory\Traits;


use AaronSchmied\ModelHistory\Change;
use AaronSchmied\ModelHistory\Observers\ChangeObserver;

trait RecordsChanges
{
    /**
     * Register the change observer.
     */
    public static function bootRecordsChanges()
    {
        static::observe(ChangeObserver::class);
    }

    /**
     * Get the changes relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function changes()
    {
        return $this->morphMany(Change::class, 'subject');
    }
}
