<?php

namespace AaronSchmied\ModelHistory\Tests;

use AaronSchmied\ModelHistory\Providers\ModelHistoryServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
    }

    /**
     * Get the package service providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ModelHistoryServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setupDatabase()
    {
        include_once __DIR__ . '/../migrations/create_model_history_table.php';

        (new \CreateModelHistoryTable())->up();

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('body')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

}
