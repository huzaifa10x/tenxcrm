<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @include('profile.partials.update-profile-information-form')
    <hr class="my-40">
    @include('profile.partials.update-password-form')
    <hr class="my-40">
    @include('profile.partials.login-pin-form')
    <hr class="my-40">
    @include('profile.partials.email-notifications-form')
    <hr class="my-40">
    @include('profile.partials.delete-user-form')
</x-app-layout>
