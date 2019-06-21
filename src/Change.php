<?php
/**
 * Copyright: © 2019 Pro Sales AG
 * Author: Aaron Schmied <aaron@pro-sales.ch>
 * Date: 2019-06-20
 * Time: 17:21
 */

namespace AaronSchmied\ModelHistory;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use ReflectionException;

class Change extends Model
{
    const TYPE_CREATED = 'created';
    const TYPE_UPDATED = 'updated';
    const TYPE_DELETED = 'deleted';
    const TYPE_RESTORED = 'restored';

    /**
     * The attributes default value.
     *
     * @var array
     */
    protected $attributes = [
        'changes' => '{}'
    ];

    /**
     * The attributes casted to a different type.
     *
     * @var array
     */
    protected $casts = [
        'recorded_at' => 'datetime',
        'changes' => 'array',
    ];

    /**
     * The guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Disable the timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the table name.
     *
     * @return string
     */
    public function getTable()
    {
        return config('modelhistory.table_name');
    }

    /**
     * Get the subject relation.
     *
     * @return MorphTo
     */
    public function subject()
    {
        try {
            $subjectReflection = new ReflectionClass($this->getAttribute('subject_type'));

            if (array_key_exists(SoftDeletes::class, $subjectReflection->getTraits())) {
                return $this
                    ->morphTo('subject')
                    ->withTrashed();
            }
        } catch (ReflectionException $exception) {
            // Ignoring the trashed
        }
        return $this
            ->morphTo('subject');
    }

    /**
     * Get the author relation.
     *
     * @return MorphTo
     */
    public function author()
    {
        return $this->morphTo('author');
    }

    /**
     * Scope a query to only fetch the changes within the given time.
     *
     * @param Builder         $query
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     *
     * @return Builder
     */
    public function scopeWhereRecordedBetween(Builder $query, CarbonInterface $from, CarbonInterface $to)
    {
        return $query->whereBetween('recorded_at', [$from, $to]);
    }

    /**
     * Scope the query to only fetch the changes for the given subject.
     *
     * @param Builder $query
     * @param Model   $subject
     *
     * @return Builder
     */
    public function scopeWhereSubject(Builder $query, Model $subject)
    {
        return $query->where([
             'subject_type' => get_class($subject),
             'subject_id' => $subject->getKey(),
        ]);
    }

    /**
     * Scope a query to only fetch the changes for the given author.
     *
     * @param Builder         $query
     * @param Authenticatable $author
     *
     * @return Builder
     */
    public function scopeWhereAuthor(Builder $query, Authenticatable $author)
    {
        return $query->where([
            'author_type' => get_class($author),
            'author_id' => $author->getAuthIdentifier(),
        ]);
    }

    /**
     * Scope a query to only fetch the given type(s).
     *
     * @param Builder       $query
     * @param string|array  $type
     *
     * @return Builder
     */
    public function scopeWhereType(Builder $query, $type)
    {
        if (is_array($type)) {
            return $query->whereIn('change_type', $type);
        }
        return $query->where('change_type', '=', $type);
    }



}
