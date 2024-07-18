<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

@section('title', __('Lost Password'))
@section('outerRow', true)

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-2">{{ __('Forgot Your Password?') }}</h1>
                <p class="mb-4">{{ __('We get it, stuff happens. Just enter your email address below and we\'ll send you a link to reset your password.') }}</p>
            </div>
            <form class="user" wire:submit="sendPasswordResetLink">
                <div class="form-group">
                    <input
                        type="email" class="form-control form-control-user" id="email" placeholder="{{ __('Email Address') }}"
                        name="email" wire:model="email" required autofocus
                    >
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Get New Password') }}
                </button>
            </form>
            <x-common.message-error :multiple="true" :message="$errors->unique()" />
            <x-common.message-success :message="session('status')" />
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
