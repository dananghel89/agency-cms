<?php

use Livewire\Volt\Component;
use App\Models\Translation;

new class extends Component
{
    public $item;
    public $delete;

    public string $key = '';
    public string $replace = '';

    protected array $rules = [
        'key' => ['required', 'string', 'max:1000', 'unique:'.Translation::class],
        'replace' => ['required', 'string', 'max:1000'],
    ];

    public function mount(): void
    {
        if (!$this->item || $this->delete) return;

        $this->key = $this->item->key;
        $this->replace = $this->item->replace;
    }

    public function store(): void
    {
        $validated = $this->validate($this->rules);

        $string = Translation::create($validated);

        Session::flash('status', __('String created.'));

        $this->redirectRoute('strings.edit', ['string' => $string->id]);
    }

    public function update(): void
    {
        $string = $this->item;

        $validated = $this->validate($this->rules);

        $string->fill($validated);

        $string->save();

        $this->dispatch('string-updated');
    }

    public function destroy(): void
    {
        $string = $this->item;

        $string->delete();

        Session::flash('status', __('String deleted.'));

        $this->redirectRoute('strings.index');
    }
}; ?>

@if (!$delete)

<div class="card-wrapper">

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="admin" wire:submit="{{ !$item ? 'store' : 'update' }}">
            <x-common.form-item type="textarea" name="key" class="no-resize" rows="6" required>
                {{ __('Key') }}
            </x-common.form-item>

            <x-common.form-item type="textarea" name="replace" class="no-resize" rows="6" required>
                {{ __('Replace') }}
            </x-common.form-item>

            <div class="form-group mt-4">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ !$item ? __('Add New') : __('Save') }}
                    </button>

                    @if ($item)
                        <button
                            type="button" class="btn btn-outline-danger btn-sm"
                            wire:click="destroy()"
                            wire:confirm="{{ __('Are you sure to delete this string?') }}"
                        >
                            {{ __('Delete') }}
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

</div>

@else

<div>
    //
</div>

@endif
