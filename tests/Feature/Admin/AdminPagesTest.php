<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public static function adminPages(): array
    {
        return [
            'dashboard' => ['dashboard'],
            'profile' => ['profile'],
            'options' => ['options'],
            'pages.index' => ['pages.index'],
            'services.index' => ['services.index'],
            'portfolio.index' => ['portfolio.index'],
            'timeline.index' => ['timeline.index'],
            'team.index' => ['team.index'],
            'contacts.index' => ['contacts.index'],
            'contacts.show' => ['contacts.show'],
            'strings.index' => ['strings.index'],
            'strings.create' => ['strings.create'],
            'strings.edit' => ['strings.edit'],
            'users.index' => ['users.index'],
        ];
    }

    #[DataProvider('adminPages')]
    public function test_admin_page_renders_for_authenticated_users(string $routeName): void
    {
        $this->actingAs(User::factory()->create())
            ->get($this->url($routeName))
            ->assertOk();
    }

    #[DataProvider('adminPages')]
    public function test_admin_page_redirects_guests_to_the_login(string $routeName): void
    {
        $this->get($this->url($routeName))
            ->assertRedirect(route('login'));
    }

    private function url(string $routeName): string
    {
        return match ($routeName) {
            'contacts.show' => route($routeName, Contact::factory()->create()),
            'strings.edit' => route($routeName, Translation::factory()->create()),
            default => route($routeName),
        };
    }
}
