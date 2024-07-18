@section('title', __('Edit String'))
@section('slug', 'strings')

<x-admin-layout>

<x-common.message-success type="flash" :message="session('status')" />

<x-common.message-success type="flash" on="string-updated" :message="__('String updated.')" />

<livewire:admin.string :item="$item" />

</x-admin-layout>
