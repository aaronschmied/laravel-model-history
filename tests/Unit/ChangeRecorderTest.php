<?php

namespace AaronSchmied\ModelHistory\TestsTests\Unit;

use AaronSchmied\ModelHistory\Change;
use AaronSchmied\ModelHistory\Tests\Models\Article;
use AaronSchmied\ModelHistory\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeRecorderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_records_the_created_change()
    {
        $article = Article::create(['title' => 'Test']);

        $this->assertCount(1, $article->changes);

        $change = $article->changes->last();

        $this->assertEquals(Change::TYPE_CREATED, $change->change_type);
    }

    /**
     * @test
     */
    public function it_records_the_updated_change()
    {
        $article = Article::create(['title' => 'Test']);
        $article->update(['body' => 'Test again']);
        $this->assertCount(2, $article->changes);

        $change = $article->changes->last();

        $this->assertEquals(Change::TYPE_UPDATED, $change->change_type);
    }

    /**
     * @test
     */
    public function it_records_the_deleted_change()
    {
        $article = Article::create(['title' => 'Test']);
        $article->delete();

        $this->assertCount(2, $article->changes);

        $change = $article->changes->last();

        $this->assertEquals(Change::TYPE_DELETED, $change->change_type);
    }

    /**
     * @test
     */
    public function it_records_the_restored_change()
    {
        $article = Article::create(['title' => 'Test']);
        $article->delete();
        $article->restore();

        // Assert count 4 since a deletion counts as an update
        $this->assertCount(4, $article->changes);

        $change = $article->changes->last();

        $this->assertEquals(Change::TYPE_RESTORED, $change->change_type);
    }

    /**
     * @test
     */
    public function it_only_records_the_changed_attributes()
    {
        $article = Article::create(['title' => 'Test']);
        $article->update(['body' => 'Test again']);
        $this->assertCount(2, $article->changes);

        $change = $article->changes->last();

        $this->assertEquals(Change::TYPE_UPDATED, $change->change_type);

        $before = $change->changes['before'];
        $after = $change->changes['after'];

        $this->assertNull($before['body']);
        $this->assertEquals('Test again', $after['body']);
    }

}
