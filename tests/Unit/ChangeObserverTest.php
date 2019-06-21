<?php


namespace AaronSchmied\ModelHistory\Tests\Unit;


use AaronSchmied\ModelHistory\Observers\ChangeObserver;
use AaronSchmied\ModelHistory\Tests\TestCase;

class ChangeObserverTest extends TestCase
{
    /**
     * ChangeObserver .
     *
     * @test
     */
    public function it_has_the_methods_to_observer_the_model_events()
    {
        $changeObserver = new ChangeObserver();

        $this->assertTrue(method_exists($changeObserver, 'created'));

        $this->assertTrue(method_exists($changeObserver, 'updated'));

        $this->assertTrue(method_exists($changeObserver, 'deleted'));

        $this->assertTrue(method_exists($changeObserver, 'restored'));

    }

}
