<!-- resources/views/companies/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT COMPANY - ') . $company->name }}
    </x-slot>
    @if ($userPermissions->contains('can manage companies'))
        <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label">Company Name</label>
                            <input value="{{ $company->name }}" type="text" name="name" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Website</label>
                            <input value="{{ $company->website }}" type="text" name="website" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Country</label>
                            <select required id="country" name="country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="UAE" {{ $company->country == 'UAE' ? 'selected' : '' }}>UAE</option>
                                <option value="SAUDI ARABIA"
                                    {{ $company->country == 'SAUDI ARABIA' ? 'selected' : '' }}>SAUDI ARABIA</option>
                                <option value="USA" {{ $company->country == 'USA' ? 'selected' : '' }}>USA</option>
                                <option value="OTHER" {{ $company->country == 'OTHER' ? 'selected' : '' }}>OTHER
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6 city-wrapper">
                            <label class="form-label">Company City</label>
                            <select required id="city" name="city" class="form-control">
                                <option value="{{ $company->city }}" selected>{{ $company->city }}</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="logo">
                                <img src="{{ asset('/public/storage/') }}/{{ $company->logo }}" class="img-fluid"
                                    alt="Logo" width="50"> {{ $company->name }}
                            </label>
                            <input type="file" name="logo" id="logo" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary-600 radius-8">Update Company</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                let cities = {
                    "UAE": ["Dubai", "Abu Dhabi", "Sharjah", "Other"],
                    "SAUDI ARABIA": ["Riyadh", "Jeddah", "Dammam", "Other"],
                    "USA": ["New York", "Los Angeles", "Chicago", "Other"],
                    "OTHER": ["Other"]
                };

                function updateCities() {
                    let country = $("#country").val();
                    let cityDropdown = $("#city");
                    cityDropdown.empty().append('<option value="">Select City</option>');

                    if (country && cities[country]) {
                        $.each(cities[country], function(index, city) {
                            let selected = (city.toLowerCase() === "{{ strtolower($company->city) }}") ?
                                "selected" : "";
                            cityDropdown.append('<option value="' + city + '" ' + selected + '>' + city +
                                '</option>');
                        });
                    }
                }
                $("#country").change(updateCities);
                updateCities();
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
