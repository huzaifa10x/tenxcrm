<!-- resources/views/companies/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><strong>Company Name:</strong> {{ $company->name }}</p>
                    <p><strong>Website:</strong> {{ $company->website }}</p>
                    <p><strong>City:</strong> {{ $company->city }}</p>
                    <p><strong>Country:</strong> {{ $company->country }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
