<?php

use Livewire\Volt\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\ContactForm;

new class extends Component
{
    public $name;
    public $email;
    public $phone;
    public $message;

    public function submit()
    {
        $this->resetErrorBag('form');

        $throttleKey = 'contact:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $this->addError('form', __('Too many messages. Please try again in :minutes minutes.', [
                'minutes' => ceil(RateLimiter::availableIn($throttleKey) / 60),
            ]));

            return;
        }

        $this->email = Str::lower(trim((string) $this->email));

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:9'],
            'message' => ['required', 'string', 'min:5', 'max:300'],
        ]);

        Contact::create($validated);

        RateLimiter::hit($throttleKey, 600);

        $this->sendMail();
        $this->reset(['name', 'email', 'phone', 'message']);

        $this->dispatch('contact-success');
    }

    public function sendMail()
    {
        $adminEmail = option('admin_email');

        if (empty($adminEmail)) return;

        Mail::to($adminEmail)->send(new ContactForm(
            $this->name, $this->email, $this->phone, $this->message
        ));
    }
}; ?>

<!-- Contact -->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">{{ __('Contact Us') }}</h2>
            <h3 class="section-subheading text-white">{{ __('Lorem ipsum dolor sit amet consectetur.') }}</h3>
        </div>
        <form id="contactForm" wire:submit="submit">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input
                            type="text" class="form-control" id="name" placeholder="{{ __('Your Name *') }}"
                            name="name" wire:model="name" autocomplete="name" required
                        >
                        <x-common.feedback-invalid :messages="$errors->get('name')" />
                    </div>
                    <div class="form-group">
                        <input
                            type="email" class="form-control" id="email" placeholder="{{ __('Your Email *') }}"
                            name="email" wire:model="email" autocomplete="email" required
                        >
                        <x-common.feedback-invalid :messages="$errors->get('email')" />
                    </div>
                    <div class="form-group mb-md-0">
                        <input
                            type="tel" class="form-control" id="phone" placeholder="{{ __('Your Phone *') }}"
                            name="phone" wire:model="phone" autocomplete="tel" required
                        >
                        <x-common.feedback-invalid :messages="$errors->get('phone')" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea
                            class="form-control no-resize" id="message" placeholder="{{ __('Your Message *') }}"
                            name="message" wire:model="message" required
                        ></textarea>
                        <x-common.feedback-invalid :messages="$errors->get('message')" />
                    </div>
                </div>
            </div>

            <x-common.message-flash on="contact-success">
                <div id="submitSuccessMessage">
                    <div class="text-center text-white mb-3">
                        <div class="fw-bolder">{{ __('Form submission successful!') }}</div>
                    </div>
                </div>
            </x-common.message-flash>

            <div class="text-center mb-3">
                <x-common.feedback-invalid :messages="$errors->get('form')" />
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-xl text-uppercase" id="submitButton">{{ __('Send Message') }}</button>
            </div>
        </form>
    </div>
</section>
