<?php

namespace Tests\Feature\Admin;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class StringsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_a_string_can_be_created(): void
    {
        $component = Volt::test('admin.string')
            ->set('key', 'Contact Us')
            ->set('replace', 'Get in touch')
            ->call('store')
            ->assertHasNoErrors();

        $string = Translation::firstWhere('key', 'Contact Us');

        $this->assertSame('Get in touch', $string->replace);
        $component->assertRedirect(route('strings.edit', $string));
    }

    public function test_a_string_requires_a_key_and_a_replacement(): void
    {
        Volt::test('admin.string')
            ->call('store')
            ->assertHasErrors(['key' => 'required', 'replace' => 'required']);
    }

    public function test_a_duplicate_key_is_rejected(): void
    {
        Translation::factory()->create(['key' => 'Contact Us']);

        Volt::test('admin.string')
            ->set('key', 'Contact Us')
            ->set('replace', 'Get in touch')
            ->call('store')
            ->assertHasErrors(['key' => 'unique']);

        $this->assertSame(1, Translation::count());
    }

    public function test_a_string_can_be_updated_with_a_changed_key(): void
    {
        $string = Translation::factory()->create();

        Volt::test('admin.string', ['item' => $string])
            ->set('key', 'Our Team')
            ->set('replace', 'The people')
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('string-updated');

        $this->assertSame('Our Team', $string->fresh()->key);
        $this->assertSame('The people', $string->fresh()->replace);
    }

    public function test_a_string_can_be_deleted(): void
    {
        $string = Translation::factory()->create();

        Volt::test('admin.string', ['item' => $string])
            ->call('destroy')
            ->assertRedirect(route('strings.index'));

        $this->assertModelMissing($string);
    }
}
