<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $url = route('dashboard');

        $this->redirect(
            session('url.intended', $url)
        );
    }
}; ?>

@section('title', __('Log In'))
@section('outerRow', true)

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">{{ __('Welcome Back!') }}</h1>
            </div>
            <form class="user" wire:submit="login">
                <div class="form-group">
                    <input
                        type="email" class="form-control form-control-user" id="email" placeholder="{{ __('Email Address') }}"
                        name="email" wire:model="form.email" required autofocus autocomplete="username"
                    >
                </div>
                <div class="form-group">
                    <input
                        type="password" class="form-control form-control-user" id="password" placeholder="{{ __('Password') }}"
                        name="password" wire:model="form.password" required autocomplete="current-password"
                    >
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" wire:model="form.remember">
                        <label class="custom-control-label" for="remember">{{ __('Remember me') }}</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Log In') }}
                </button>
            </form>
            <x-common.message-error :multiple="true" :message="$errors->unique()" />
            <x-common.message-success :message="session('status')" />
            <hr>
            <div class="text-center">
                @if (Route::has('register'))
                    <a class="small" href="{{ route('register') }}">{{ __('Register') }}</a>
                    <span class="mx-1">|</span>
                @endif
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
