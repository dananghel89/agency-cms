@section('title', __('View Contact'))
@section('slug', 'contacts')

<x-admin-layout>

<div class="card-wrapper">

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="admin">
            <fieldset disabled>

            <x-common.form-item value="{{ $item->name }}">
                {{ __('Name') }}
            </x-common.form-item>

            <x-common.form-item value="{{ $item->email }}">
                {{ __('Email') }}
            </x-common.form-item>

            <x-common.form-item value="{{ $item->phone }}">
                {{ __('Phone') }}
            </x-common.form-item>

            <x-common.form-item type="textarea" text="{{ $item->message }}" rows="8" class="no-resize">
                {{ __('Message') }}
            </x-common.form-item>

            </fieldset>
        </form>
    </div>
</div>

</div>

</x-admin-layout>
