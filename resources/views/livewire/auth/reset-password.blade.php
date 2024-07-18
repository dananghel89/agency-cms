<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login');
    }
}; ?>

@section('title', __('Reset Password'))

<div class="row" wire:submit="resetPassword">
    <div class="col-lg-5 d-none d-lg-block bg-password-image"></div>
    <div class="col-lg-7">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-2">{{ __('Reset Your Password') }}</h1>
                <p class="mb-4">{{ __('Enter your new password below.') }}</p>
            </div>
            <form class="user">
                <div class="form-group">
                    <input
                        type="email" class="form-control form-control-user" id="email" placeholder="{{ __('Email Address') }}"
                        name="email" wire:model="email" required autofocus autocomplete="username"
                    >
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input
                            type="password" class="form-control form-control-user" id="password" placeholder="{{ __('Password') }}"
                            name="password" wire:model="password" required autocomplete="new-password"
                        >
                    </div>
                    <div class="col-sm-6">
                        <input
                            type="password" class="form-control form-control-user" id="password_confirmation" placeholder="{{ __('Confirm Password') }}"
                            name="password_confirmation" wire:model="password_confirmation" required autocomplete="new-password"
                        >
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Reset Password') }}
                </button>
            </form>
            <x-common.message-error :multiple="true" :message="$errors->unique()" />
            <hr>
            <div class="text-center">
                <a class="small" href="{{ route('login') }}">{{ __('Log in') }}</a>
                @if (Route::has('register'))
                    <span class="mx-1">|</span>
                    <a class="small" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            </div>
            <div class="text-center">
                <a class="small" href="{{ route('home') }}">
                    <i class="fas fa-long-arrow-alt-left mr-1"></i>
                    {{ __('Go to :website', ['website' => option('site_title')]) }}
                </a>
            </div>
        </div>
    </div>
</div>
