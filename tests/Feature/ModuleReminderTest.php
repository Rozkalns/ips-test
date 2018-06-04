<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Tests\TestCase;

class ModuleReminderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmailNotPassedThrowsException()
    {
        $this->expectException(UrlGenerationException::class);
        $this->post(route('module_reminder'));
    }
}
