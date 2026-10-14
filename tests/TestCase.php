<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Never run the (data-wiping) test suite against a real database.
        $db = (string) config('database.connections.'.config('database.default').'.database');
        if (! str_ends_with($db, '_test') && $db !== ':memory:') {
            $this->markTestSkipped("Tests need a *_test database (current: {$db}). See phpunit.xml DB_DATABASE.");
        }
    }
}
