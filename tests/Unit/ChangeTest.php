<?php


namespace AaronSchmied\ModelHistory\Tests\Unit;

use AaronSchmied\ModelHistory\Change;
use AaronSchmied\ModelHistory\Tests\Models\Article;
use AaronSchmied\ModelHistory\Tests\TestCase;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class ChangeTest extends TestCase
{
    /**
     * Make a new change model.
     *
     * @return Change
     */
    protected function makeChangeModel()
    {
        return new Change([
            'recorded_at' => Carbon::now(),
                          ]);
    }

    /**
     * @test
     */
    public function it_provides_the_change_types()
    {
        $this->assertEquals(Change::TYPE_CREATED, 'created');
        $this->assertEquals(Change::TYPE_UPDATED, 'updated');
        $this->assertEquals(Change::TYPE_DELETED, 'deleted');
        $this->assertEquals(Change::TYPE_RESTORED, 'restored');
    }

    /**
     * @test
     */
    public function it_does_not_save_timestamps()
    {
        $this->assertFalse($this->makeChangeModel()->usesTimestamps());
    }

    /**
     * @test
     */
    public function it_provides_the_relation_to_the_subject()
    {
        $this->assertInstanceOf(
            MorphTo::class,
            $this->makeChangeModel()->subject()
        );

        $article = Article::create(['title' => 'Test']);

        $change = $article->changes->last();

        $this->assertTrue($change->subject->is($article));
    }

    /**
     * @test
     */
    public function it_provides_the_relation_to_the_author()
    {
        $this->assertInstanceOf(
            MorphTo::class,
            $this->makeChangeModel()->author()
        );




    }

    /**
     * @test
     */
    public function it_contains_the_changed_attributes()
    {
        $this->assertIsArray($this->makeChangeModel()->changes);

        $article = Article::create(['title' => 'Test']);

        $this->assertIsArray($article->changes->last()->changes);
    }

    /**
     * @test
     */
    public function it_has_a_record_timestamp()
    {
        $this->assertInstanceOf(CarbonInterface::class, $this->makeChangeModel()->recorded_at);
    }

    /**
     * @test
     */
    public function it_provides_a_query_scope_for_the_subject()
    {
        $this->assertTrue(method_exists($this->makeChangeModel(), 'scopeWhereSubject'));
    }

    /**
     * @test
     */
    public function it_provides_a_scope_for_the_change_author()
    {
        $this->assertTrue(method_exists($this->makeChangeModel(), 'scopeWhereAuthor'));
    }

    /**
     * @test
     */
    public function it_provides_a_scope_for_the_change_type()
    {
        $this->assertTrue(method_exists($this->makeChangeModel(), 'scopeWhereType'));
    }
}
