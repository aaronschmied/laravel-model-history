<?php

use AaronSchmied\ModelHistory\Change;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelHistoryTable extends Migration
{
    /**
     * Run the migration.
     */
    public function up()
    {
        Schema::create(config('modelhistory.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('subject_type');
            $table->string('subject_id');

            $table->string('author_type')->nullable();
            $table->string('author_id')->nullable();

            $table->enum('change_type', [
                Change::TYPE_CREATED,
                Change::TYPE_UPDATED,
                Change::TYPE_DELETED,
                Change::TYPE_RESTORED,
            ]);

            $table->json('changes');

            $table->timestampTz('recorded_at');
        });
    }

    /**
     * Revert the migration.
     */
    public function down()
    {
        Schema::dropIfExists(config('modelhistory.table_name'));
    }

}
