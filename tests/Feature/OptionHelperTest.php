<?php

namespace Tests\Feature;

use App\Models\Options;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_config_defaults_apply_when_the_options_table_is_empty(): void
    {
        config(['project.options.site_title' => 'Default Title']);

        $this->assertSame('Default Title', option('site_title'));
        $this->assertNull(option('unknown_option'));
    }

    public function test_database_values_override_config_defaults(): void
    {
        config(['project.options.site_title' => 'Default Title']);
        Options::factory()->create(['name' => 'site_title', 'value' => 'Stored Title']);

        $this->assertSame('Stored Title', option('site_title'));
    }

    public function test_json_values_can_be_decoded(): void
    {
        Options::factory()->create([
            'name' => 'social_links',
            'value' => json_encode(['facebook' => 'https://www.facebook.com/']),
        ]);

        $this->assertSame(['facebook' => 'https://www.facebook.com/'], option('social_links', true));
        $this->assertIsString(option('social_links'));
    }
}
