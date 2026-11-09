<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];

        unset(
            $validated['first_name'],
            $validated['last_name']
        );

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirectRoute('dashboard');
    }
}; ?>

@section('title', __('Register'))

<div class="row">
    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
    <div class="col-lg-7">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">{{ __('Create an Account') }}</h1>
            </div>
            <form class="user" wire:submit="register">
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input
                            type="text" class="form-control form-control-user" id="first_name" placeholder="{{ __('First Name') }}"
                            name="first_name" wire:model="first_name" required autofocus autocomplete="first-name"
                        >
                    </div>
                    <div class="col-sm-6">
                        <input
                            type="text" class="form-control form-control-user" id="last_name" placeholder="{{ __('Last Name') }}"
                            name="last_name" wire:model="last_name" required autocomplete="last-name"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <input
                        type="email" class="form-control form-control-user" id="email" placeholder="{{ __('Email Address') }}"
                        name="email" wire:model="email" required autocomplete="username"
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
                    {{ __('Register') }}
                </button>
            </form>
            <x-common.message-error :multiple="true" :message="$errors->unique()" />
            <hr>
            <div class="text-center">
                <a class="small" href="{{ route('login') }}">{{ __('Log in') }}</a>
                <span class="mx-1">|</span>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
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
