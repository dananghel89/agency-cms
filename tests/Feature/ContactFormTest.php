<?php

namespace Tests\Feature;

use App\Mail\ContactForm;
use App\Models\Options;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_contact_form_is_validated(): void
    {
        Livewire::test('pages.contact')
            ->call('submit')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'message' => 'required',
            ]);

        $this->assertDatabaseCount('contacts', 0);
        Mail::assertNothingSent();
    }

    public function test_a_valid_message_is_stored_and_mailed_to_the_admin(): void
    {
        Options::factory()->create(['name' => 'admin_email', 'value' => 'owner@example.com']);

        $this->submitValidMessage()
            ->assertHasNoErrors()
            ->assertDispatched('contact-success')
            ->assertSet('name', null);

        $this->assertDatabaseHas('contacts', [
            'name' => 'Ana Garcia',
            'email' => 'ana@example.com',
            'phone' => '612345678',
        ]);

        Mail::assertSent(ContactForm::class, fn (ContactForm $mail) => $mail->hasTo('owner@example.com'));
    }

    public function test_an_email_typed_with_capitals_is_accepted_and_stored_in_lowercase(): void
    {
        Livewire::test('pages.contact')
            ->set('name', 'Ana Garcia')
            ->set('email', '  Ana@Example.COM ')
            ->set('phone', '612345678')
            ->set('message', 'Hello, I would like a quote.')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('contacts', ['email' => 'ana@example.com']);
    }

    public function test_no_mail_is_sent_when_the_admin_email_is_empty(): void
    {
        Options::factory()->create(['name' => 'admin_email', 'value' => '']);

        $this->submitValidMessage()->assertHasNoErrors();

        $this->assertDatabaseCount('contacts', 1);
        Mail::assertNothingSent();
    }

    public function test_the_fourth_message_within_ten_minutes_is_rejected(): void
    {
        Options::factory()->create(['name' => 'admin_email', 'value' => 'owner@example.com']);

        foreach (range(1, 3) as $attempt) {
            $this->submitValidMessage()->assertHasNoErrors();
        }

        $this->submitValidMessage()
            ->assertHasErrors('form')
            ->assertNotDispatched('contact-success');

        $this->assertDatabaseCount('contacts', 3);
        Mail::assertSent(ContactForm::class, 3);

        $this->travel(11)->minutes();

        $this->submitValidMessage()->assertHasNoErrors();
        $this->assertDatabaseCount('contacts', 4);
    }

    private function submitValidMessage()
    {
        return Livewire::test('pages.contact')
            ->set('name', 'Ana Garcia')
            ->set('email', 'ana@example.com')
            ->set('phone', '612345678')
            ->set('message', 'Hello, I would like a quote.')
            ->call('submit');
    }
}
