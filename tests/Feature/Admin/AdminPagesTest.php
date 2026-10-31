<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Team;
use App\Models\Timeline;
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

    public static function unfinishedSections(): array
    {
        return [
            'pages' => ['pages', Page::class],
            'services' => ['services', Service::class],
            'portfolio' => ['portfolio', Portfolio::class],
            'timeline' => ['timeline', Timeline::class],
            'team' => ['team', Team::class],
            'users' => ['users', User::class],
        ];
    }

    #[DataProvider('unfinishedSections')]
    public function test_unfinished_create_and_edit_pages_say_not_implemented(string $section, string $model): void
    {
        $this->actingAs(User::factory()->create());

        $item = $model::factory()->create();

        foreach ([route("{$section}.create"), route("{$section}.edit", $item)] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Not implemented yet')
                ->assertSee('id="accordionSidebar"', false)
                ->assertSee(route("{$section}.index"));
        }
    }

    #[DataProvider('unfinishedSections')]
    public function test_edit_page_of_an_unknown_item_is_not_found(string $section): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route("{$section}.edit", 999))
            ->assertNotFound();
    }

    #[DataProvider('unfinishedSections')]
    public function test_lists_have_no_dead_delete_buttons(string $section, string $model): void
    {
        $this->actingAs(User::factory()->create());

        $model::factory()->create();

        $this->get(route("{$section}.index"))
            ->assertOk()
            ->assertDontSee('fa-trash-alt', false);
    }

    public function test_contact_and_string_lists_have_no_dead_delete_buttons(): void
    {
        $this->actingAs(User::factory()->create());

        Contact::factory()->create();
        Translation::factory()->create();

        $this->get(route('contacts.index'))->assertOk()->assertDontSee('fa-trash-alt', false);
        $this->get(route('strings.index'))->assertOk()->assertDontSee('fa-trash-alt', false);
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
