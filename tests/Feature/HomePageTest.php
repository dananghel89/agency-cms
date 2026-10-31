<?php

namespace Tests\Feature;

use App\Models\Options;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Team;
use App\Models\Timeline;
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

    public function test_home_page_hides_draft_items(): void
    {
        Service::factory()->create(['title' => 'Published service']);
        Service::factory()->draft()->create(['title' => 'Draft service']);
        Portfolio::factory()->create(['client' => 'Published client']);
        Portfolio::factory()->draft()->create(['client' => 'Draft client']);
        Timeline::factory()->create(['subtitle' => 'Published milestone']);
        Timeline::factory()->draft()->create(['subtitle' => 'Draft milestone']);
        Team::factory()->create(['name' => 'Published member']);
        Team::factory()->draft()->create(['name' => 'Draft member']);

        $this->get('/')
            ->assertOk()
            ->assertSee(['Published service', 'Published client', 'Published milestone', 'Published member'])
            ->assertDontSee(['Draft service', 'Draft client', 'Draft milestone', 'Draft member']);
    }

    public function test_home_page_orders_items_by_position(): void
    {
        Service::factory()->create(['title' => 'Second service', 'position' => 2]);
        Service::factory()->create(['title' => 'First service', 'position' => 1]);

        $this->get('/')->assertSeeInOrder(['First service', 'Second service']);
    }

    public function test_home_page_title_uses_the_site_title_option(): void
    {
        Options::factory()->create(['name' => 'site_title', 'value' => 'My Agency']);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Home | My Agency</title>', false);
    }
}
