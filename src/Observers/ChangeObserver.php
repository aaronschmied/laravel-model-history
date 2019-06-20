<?php
/**
 * Copyright: © 2019 Pro Sales AG
 * Author: Aaron Schmied <aaron@pro-sales.ch>
 * Date: 2019-06-20
 * Time: 17:33
 */

namespace AaronSchmied\ModelHistory\Observers;


use AaronSchmied\ModelHistory\Change;
use AaronSchmied\ModelHistory\Contracts\ChangeRecorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChangeObserver
{
    /**
     * Get the change recorder.
     *
     * @return ChangeRecorder
     */
    private function recorder()
    {
        return resolve(ChangeRecorder::class);
    }

    /**
     * Handle the Model "created" event.
     *
     * @param  Model $model
     * @return void
     */
    public function created(Model $model)
    {
        $this
            ->recorder()
            ->record(Change::TYPE_CREATED, $model, Auth::user());
    }

    /**
     * Handle the Model "updated" event.
     *
     * @param  Model $model
     * @return void
     */
    public function updated(Model $model)
    {
        $this
            ->recorder()
            ->record(Change::TYPE_UPDATED, $model, Auth::user());
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @param  Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $this
            ->recorder()
            ->record(Change::TYPE_DELETED, $model, Auth::user());
    }

    /**
     * Handle the Model "restored" event.
     *
     * @param  Model $model
     * @return void
     */
    public function restored(Model $model)
    {
        $this
            ->recorder()
            ->record(Change::TYPE_RESTORED, $model, Auth::user());
    }
}
