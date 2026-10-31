<?php

namespace Tests\Feature;

use App\Models\Options;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_the_seeded_content(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('E-Commerce')
            ->assertSee('Threads')
            ->assertSee('An Agency is Born')
            ->assertSee('Parveen Anand')
            ->assertSeeLivewire('pages.contact');
    }

    public function test_home_page_title_uses_the_site_title_option(): void
    {
        Options::factory()->create(['name' => 'site_title', 'value' => 'My Agency']);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Home | My Agency</title>', false);
    }
}
