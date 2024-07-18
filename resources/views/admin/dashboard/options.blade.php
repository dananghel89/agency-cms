@section('title', __('Options'))
@section('slug', 'options')

<x-admin-layout>

<x-common.message-success type="flash" on="options-updated" :message="__('Options updated.')" />

<livewire:admin.options :items="$items" :socialNetworks="$socialNetworks" />

</x-admin-layout>
