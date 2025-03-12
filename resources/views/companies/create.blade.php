<!-- resources/views/companies/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('Create Company') }}
    </x-slot>
    @if ($userPermissions->contains('can manage companies'))
        <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card mb-10">
                <div class="card-header">
                    <span class="fw-bold">Company Details</span>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label">Company Name</label>
                            <input required type="text" name="name" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Website</label>
                            <input required type="text" name="website" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Country</label>
                            <select required id="country" name="country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="UAE">UAE</option>
                                <option value="SAUDI ARABIA">SAUDI ARABIA</option>
                                <option value="USA">USA</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                        <div class="col-lg-6 city-wrapper" style="display: none">
                            <label class="form-label">Company City</label>
                            <select required id="city" name="city" class="form-control">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-10">
                <div class="card-header">
                    <span class="fw-bold">Contact person Details</span>
                </div>
                <div class="card-body">
                    <div id="companyDetailsContainer">
                        <div class="row gy-3 company-detail">
                            <div class="col-lg-6">
                                <label class="form-label">Name</label>
                                <input required name="detail_name[]" type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Email</label>
                                <input required name="detail_email[]" type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Phone Number</label>
                                <input required name="detail_phone[]" type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">City</label>
                                <input required name="detail_city[]" type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Website</label>
                                <input required name="detail_website[]" type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-danger remove-detail mt-3">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <button type="button" id="addMoreDetails" class="btn btn-success btn-sm">Add More</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                $("#addMoreDetails").click(function() {
                    var newDetail = $(".company-detail:first").clone();
                    newDetail.find("input").val(""); // Clear input fields
                    $("#companyDetailsContainer").append(newDetail);
                });

                $(document).on("click", ".remove-detail", function() {
                    if ($(".company-detail").length > 1) {
                        $(this).closest(".company-detail").remove();
                    } else {
                        alert("At least one detail is required!");
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                let cities = {
                    "UAE": [
                        "Abu Dhabi",
                        "Dubai",
                        "Sharjah",
                        "Ajman",
                        "Umm Al Quwain",
                        "Ras Al Khaimah",
                        "Fujairah",
                    ],
                    "SAUDI ARABIA": ["Other"],
                    "USA": ["Other"],
                    "OTHER": ["Other"]
                };

                $("#country").change(function() {
                    let country = $(this).val();
                    let cityDropdown = $("#city");
                    $('.city-wrapper').slideDown();
                    cityDropdown.empty().append('<option value="">Select City</option>');

                    if (country && cities[country]) {
                        $.each(cities[country], function(index, city) {
                            cityDropdown.append('<option value="' + city.toLowerCase() + '">' + city +
                                '</option>');
                        });
                    }
                });
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
