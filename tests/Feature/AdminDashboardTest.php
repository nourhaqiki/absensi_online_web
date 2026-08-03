<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard_can_be_rendered(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }
}
