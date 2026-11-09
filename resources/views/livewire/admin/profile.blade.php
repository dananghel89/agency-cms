<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        $user = Auth::user();

        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);

        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div class="card-wrapper">

<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Profile Information') }}</h6>
    </div>
    <div class="card-body">
        <p class="mb-4">{{ __('Update your account\'s profile information and email address.') }}</p>

        <form class="admin" wire:submit="updateProfileInformation">
            <x-common.form-item type="text" name="name" required autocomplete="name">
                {{ __('Name') }}
            </x-common.form-item>

            <x-common.form-item type="email" name="email" required autocomplete="username">
                {{ __('Email Address') }}
            </x-common.form-item>

            <x-common.form-submit>
                {{ __('Save Changes') }}
            </x-common.form-submit>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Update Password') }}</h6>
    </div>
    <div class="card-body">
        <p class="mb-4">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

        <form class="admin" wire:submit="updatePassword">
            <x-common.form-item type="password" name="current_password" autocomplete="current-password">
                {{ __('Current Password') }}
            </x-common.form-item>

            <x-common.form-item type="password" name="password" autocomplete="new-password">
                {{ __('New Password') }}
            </x-common.form-item>

            <x-common.form-item type="password" name="password_confirmation" autocomplete="new-password">
                {{ __('Confirm Password') }}
            </x-common.form-item>

            <x-common.form-submit>
                {{ __('Save Changes') }}
            </x-common.form-submit>
        </form>
    </div>
</div>

</div>
