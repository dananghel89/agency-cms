<div {{ $attributes->merge(['class' => 'form-group mt-4']) }}>
    <button type="submit" class="btn btn-primary btn-sm">
        {{ $slot->isEmpty() ? __('Submit') : $slot }}
    </button>
</div>
