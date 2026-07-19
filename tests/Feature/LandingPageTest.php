<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_user_can_access_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_user_can_access_cara_kerja_section()
    {
        $response = $this->get('/');
        $response->assertSee('Cara Kerja');
    }

    public function test_user_can_access_faq_page()
    {
        $response = $this->get('/');
        $response->assertSee('FAQ');
    }
}
