<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Volt\Volt;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSeeVolt('auth.forgot-password');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Volt::test('auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendPasswordResetLink')
            ->assertHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->get('/reset-password/'.$token.'?email='.urlencode($user->email))
            ->assertOk()
            ->assertSeeVolt('auth.reset-password')
            ->assertSee('<form class="user" wire:submit="resetPassword">', false);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        Volt::test('auth.reset-password', ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword')
            ->assertHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
