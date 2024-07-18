<?php

use App\Models\Options;
use Illuminate\Support\Arr;
use Livewire\Volt\Component;

new class extends Component
{
    public $items;
    public $socialNetworks;

    public string $site_title = '';
    public string $admin_email = '';
    public array $social_links = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $options = $this->items->pluck('value', 'name');

        foreach ($options as $optionName => $optionValue) {
            if (!isset($this->$optionName)) continue;

            if (is_array($this->$optionName)) {
                $optionValue = $optionValue ? json_decode($optionValue, true) : [];
            }

            $this->$optionName = $optionValue;
        }
    }

    public function update(): void
    {
        $options = $this->items->pluck('name', 'id');

        $validated = $this->validate([
            'site_title' => ['string', 'max:255'],
            'admin_email' => ['string', 'lowercase', 'email', 'max:255'],
            'social_links.*' => ['string', 'lowercase', 'url', 'max:255'],
        ]);

        foreach ( $options as $optionId => $optionName ) {
            $option = Options::find($optionId);

            $option->update([
                'value' => Arr::get($validated, $optionName),
            ]);
        }

        $this->dispatch('options-updated');
    }
}; ?>

<div class="card-wrapper">

<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Site Options') }}</h6>
    </div>
    <div class="card-body">
        <p class="mb-4">{{ __('Customize website features, layout, and settings for optimal functionality.') }}</p>

        <form class="admin" wire:submit="update">
            <x-common.form-item type="text" name="site_title" required autocomplete="name">
                {{ __('Site Title') }}
            </x-common.form-item>

            <x-common.form-item type="email" name="admin_email" required autocomplete="username">
                {{ __('Admin Email Address') }}
            </x-common.form-item>

            <x-common.form-row label="{{ __('Social Links') }}" class="mt-5">
                @foreach ($socialNetworks as $networkItem)
                    @php
                        $itemLabel = sprintf('%s_link', $networkItem['name']);
                        $itemInput = sprintf('social_links[%s]', $networkItem['name']);
                        $itemModel = sprintf('social_links.%s', $networkItem['name']);
                    @endphp

                    <x-common.input-group type="text" id="{{ $itemLabel }}" name="{{ $itemInput }}" wire:model="{{ $itemModel }}">
                        <i class="fab {{ $networkItem['icon'] }} fa-fw"></i>
                    </x-common.input-group>
                @endforeach
            </x-common.form-row>

            <x-common.form-submit>
                {{ __('Save Changes') }}
            </x-common.form-submit>
        </form>
    </div>
</div>

</div>
