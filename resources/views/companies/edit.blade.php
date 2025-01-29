<!-- resources/views/companies/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Company') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('companies.update', $company->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full" value="{{ $company->name }}" required />
                        </div>

                        <div class="mb-4">
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" id="website" name="website" class="mt-1 block w-full" value="{{ $company->website }}" required />
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="city" name="city" class="mt-1 block w-full" value="{{ $company->city }}" required />
                        </div>

                        <div class="mb-4">
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" id="country" name="country" class="mt-1 block w-full" value="{{ $company->country }}" required />
                        </div>

                        <button type="submit" class="btn btn-primary">Update Company</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
