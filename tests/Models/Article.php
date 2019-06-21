<?php

namespace AaronSchmied\ModelHistory\Tests\Models;

use AaronSchmied\ModelHistory\Traits\RecordsChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use RecordsChanges, SoftDeletes;

    protected $guarded = [];
}
