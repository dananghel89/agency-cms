<?php

namespace Tests\Feature\Admin;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_links_are_escaped_in_the_admin_team_list(): void
    {
        Team::factory()->create([
            'social_links' => [
                'facebook' => '"><script>alert(1)</script>',
                'twitter' => 'javascript:alert(2)',
                'linkedin' => 'https://www.linkedin.com/in/member',
            ],
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('team.index'))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('href="javascript:', false)
            ->assertSee('href="https://www.linkedin.com/in/member"', false);
    }
}
