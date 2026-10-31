<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_unknown_slug_is_not_found(): void
    {
        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_a_draft_page_is_not_found(): void
    {
        $page = Page::factory()->draft()->create();

        $this->get('/'.$page->slug)->assertNotFound();
    }

    public function test_a_published_page_shows_its_title_description_and_escaped_content(): void
    {
        $page = Page::factory()->create([
            'slug' => 'about-us',
            'title' => 'About Us',
            'description' => 'Who we are',
            'content' => "First line\n<script>alert(1)</script>",
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('<title>About Us |', false)
            ->assertSee('<meta name="description" content="Who we are">', false)
            ->assertSee('First line<br />', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);

        $this->assertSame('Who we are', $page->fresh()->description);
    }
}
