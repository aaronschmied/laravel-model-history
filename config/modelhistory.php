<?php

return [

    /**
     * The changes table name.
     */
    'table_name' => 'model_changes',

    /**
     * The recorder class used to record the model changes.
     *
     * To use your own, implement the AaronSchmied\ModelHistory\Contracts\ChangeRecorder interface.
     */
    'change_recorder' => AaronSchmied\ModelHistory\ChangeRecorder::class,

    /**
     * Ignore the created_at and updated_at timestamps.
     */
    'record_timestamps' => false,
];
