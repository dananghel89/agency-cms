<?php

namespace Tests\Feature\Admin;

use App\Models\Options;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OptionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        foreach (['site_title' => 'Old Title', 'admin_email' => 'old@example.com', 'social_links' => null] as $name => $value) {
            Options::factory()->create(['name' => $name, 'value' => $value]);
        }
    }

    public function test_existing_options_can_be_updated(): void
    {
        $this->optionsComponent()
            ->set('site_title', 'New Title')
            ->set('admin_email', 'owner@example.com')
            ->set('social_links', ['facebook' => 'https://www.facebook.com/agency'])
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('options-updated');

        $options = Options::pluck('value', 'name');

        $this->assertSame('New Title', $options['site_title']);
        $this->assertSame('owner@example.com', $options['admin_email']);
        $this->assertSame(['facebook' => 'https://www.facebook.com/agency'], json_decode($options['social_links'], true));
    }

    public function test_options_are_validated(): void
    {
        $this->optionsComponent()
            ->set('admin_email', 'not-an-email')
            ->set('social_links', ['facebook' => 'not-a-url'])
            ->call('update')
            ->assertHasErrors(['admin_email' => 'email', 'social_links.facebook' => 'url']);

        $this->assertSame('old@example.com', Options::firstWhere('name', 'admin_email')->value);
    }

    public function test_missing_option_rows_are_created_on_save(): void
    {
        Options::query()->delete();

        $this->optionsComponent()
            ->assertSet('site_title', config('project.options.site_title'))
            ->set('site_title', 'New Title')
            ->set('admin_email', 'owner@example.com')
            ->set('social_links', ['facebook' => 'https://www.facebook.com/agency', 'twitter' => ''])
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('options-updated');

        $options = Options::pluck('value', 'name');

        $this->assertSame('New Title', $options['site_title']);
        $this->assertSame('owner@example.com', $options['admin_email']);
        $this->assertSame('https://www.facebook.com/agency', json_decode($options['social_links'], true)['facebook']);
    }

    private function optionsComponent()
    {
        return Livewire::test('admin.options', [
            'socialNetworks' => Options::socialNetworks(),
        ]);
    }
}
