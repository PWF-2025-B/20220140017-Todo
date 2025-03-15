<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTesting extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $appName =env("YOUTUBE");

        self::assertEquals("Vin",$appName);
    }
}
