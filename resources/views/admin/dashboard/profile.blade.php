@section('title', __('Profile'))
@section('slug', 'profile')

<x-admin-layout>

<x-common.message-success type="flash" on="profile-updated" :message="__('Profile updated.')" />

<x-common.message-success type="flash" on="password-updated" :message="__('Password updated.')" />

<livewire:admin.profile />

</x-admin-layout>
