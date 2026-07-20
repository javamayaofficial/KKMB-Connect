<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_landing_page_terbuka(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_halaman_login_terbuka(): void
    {
        $this->get('/masuk')->assertStatus(200);
    }
}
