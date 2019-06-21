<?php
/**
 * Copyright: © 2019 Pro Sales AG
 * Author: Aaron Schmied <aaron@pro-sales.ch>
 * Date: 2019-06-20
 * Time: 17:29
 */

namespace AaronSchmied\ModelHistory;

use AaronSchmied\ModelHistory\Contracts\ChangeRecorder as ChangeRecorderContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ChangeRecorder implements ChangeRecorderContract
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
    public function record(string $type, Model $subject, ?Authenticatable $author): void
    {
        Change::create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->getKey(),

            'author_type' => $author ? get_class($author) : null,
            'author_id' => optional($author)->getAuthIdentifier(),

            'change_type' => $type,

            'changes' => $this->getChangesForSubject($subject, $type),
            'recorded_at' => now(),
        ]);
    }

    /**
     * Get the changes for the given model.
     *
     * @param Model $subject
     * @param       $type
     *
     * @return array
     */
    private function getChangesForSubject(Model $subject, $type)
    {
        $before = [];
        $after = [];

        switch ($type) {
            case Change::TYPE_CREATED:
            case Change::TYPE_RESTORED:
                $after = $subject->getAttributes();
                break;
            case Change::TYPE_DELETED:
                $before = $subject->getAttributes();
                break;
            case Change::TYPE_UPDATED:
                foreach ($subject->getAttributes() as $key => $_) {
                    Arr::set($before, $key, $subject->getOriginal($key));
                }
                $after = $subject->getAttributes();
            break;
        }
        return compact('before', 'after');
    }
}
