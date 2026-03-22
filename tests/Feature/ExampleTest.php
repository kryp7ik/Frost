<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_login_page_returns_successful_response()
    {
        $response = $this->get('/users/login');

        $response->assertStatus(200);
    }
}
