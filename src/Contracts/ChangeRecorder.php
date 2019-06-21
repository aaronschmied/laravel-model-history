<?php
/**
 * Copyright: © 2019 Pro Sales AG
 * Author: Aaron Schmied <aaron@pro-sales.ch>
 * Date: 2019-06-20
 * Time: 17:36
 */

namespace AaronSchmied\ModelHistory\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface ChangeRecorder
{
    /**
     * Record a change to the given model.
     *
     * @param string               $type
     * @param Model                $subject
     * @param Authenticatable|null $author
     *
     * @return void
     */
    public function record(string $type, Model $subject, ?Authenticatable $author): void;
}
