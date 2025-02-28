<x-app-layout>
    <x-slot name="header">
        {{ __('Create Project') }}
    </x-slot>
    @if ($userPermissions->contains('can add projects'))
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('projects.store') }}" method="POST"
                    id="create-project-form">
                    @csrf
                    <div class="row gy-3">

                        <div class="col-lg-12">
                            <label class="form-label" for="title">Project Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="title">Project Description</label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label" for="company_id">Company</label>
                            <select class="form-control w-100" name="company_id" id="company_id">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4">
                                <label class="form-label" for="sales_manager_ids">Sales Managers</label>
                                <select class="form-control w-100" name="sales_manager_ids[]" id="sales_manager_ids">
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SALES MANAGER')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="account_manager_ids">Account Managers</label>
                                <select class="form-control w-100" name="account_manager_ids[]"
                                    id="account_manager_ids">
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('AM')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="tech_manager_ids">Tech Managers</label>
                                <select class="form-control w-100" name="tech_manager_ids[]" id="tech_manager_ids">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-lg-4">
                                <label class="form-label" for="seo_tech_ids">Seo Tech</label>
                                <select class="form-control w-100" name="seo_tech_ids[]" id="seo_tech_ids"
                                    >
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SEO TECH')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-lg-4">
                                <label class="form-label" for="seo_admin_ids">Seo Admin</label>
                                <select class="form-control w-100" name="seo_admin_ids[]" id="seo_admin_ids">
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SEO ADMIN')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="content_writers_ids">Content Writer</label>
                                <select class="form-control w-100" name="content_writers_ids[]"
                                    id="content_writers_ids">
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('CONTENT')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option selected disabled value="">Select Category</option>
                                <option value="PLATINUM">PLATINUM</option>
                                <option value="GOLD">GOLD</option>
                                <option value="SILVER">SILVER</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option selected disabled value="">Select status</option>
                                <option value="Current">Current</option>
                                <option value="Paused">Paused</option>
                                <option value="WFF">WFF</option>
                                <option value="Expired">Expired</option>
                                <option value="Debt Collection">Debt Collection</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="industry">Industry</label>
                            <select class="form-control" id="industry_id" name="industry_id" required>
                                <option selected disabled value="">Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->industry }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method_id">Payment Method</label>
                            <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                                <option selected disabled value="">Select Payment Method</option>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_start_date">contract start date</label>
                            <input type="date" class="form-control" id="contract_start_date"
                                name="contract_start_date" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">contract value</label>
                            <input type="number" class="form-control" id="contract_value" name="contract_value"
                                required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">contract term (in months)</label>
                            <input type="number" class="form-control" id="contract_term" name="contract_term"
                                required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="free_months">free months</label>
                            <input type="number" class="form-control" id="free_months" name="free_months" required>
                        </div>


                        <div class="col-lg-6">
                            <label class="form-label" for="handover_notes">Contract Copy</label>
                            <input type="file" name="contract_copy[]" multiple class="form-control">
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="handover_notes">Handover notes</label>
                            <textarea required class="form-control" name="handover_notes" id="handover_notes" cols="30" rows="10"></textarea>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="project_type">Project Type</label>
                            <select class="form-control" id="project_type" name="project_type" required>
                                <option selected disabled value="">Select Project Type</option>
                                <option value="SEO">SEO</option>
                                <option value="Google Ads">Google Ads</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Hosting">Hosting</option>
                                <option value="Hosting">Other</option>
                            </select>
                        </div>

                        <div id="extra-fields-container">
                        </div>

                        <input type="hidden" value="{{ auth()->user()->id }}" name="created_by">

                        <div class="col-lg-6 mt-3">
                            <button class="btn btn-primary" type="submit">Save Project</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#project_type').change(function() {
                    var projectType = $(this).val(); // Get selected project type
                    if (projectType) {
                        $.ajax({
                            url: '{{ route('projects-get-extra-fields') }}', // Correctly use route name
                            method: 'GET',
                            data: {
                                project_type: projectType
                            },
                            success: function(response) {
                                $('#extra-fields-container').html(response);
                                $('#channels').select2({
                                    placeholder: 'Select Channels',
                                    allowClear: true,
                                    width: '100%',
                                    closeOnSelect: true
                                });
                            }
                        });
                    } else {
                        $('#extra-fields-container').html('');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#sales_manager_ids').select2({
                    placeholder: 'Select Sales Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#account_manager_ids').select2({
                    placeholder: 'Select Account Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#tech_manager_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#seo_tech_ids').select2({
                    placeholder: 'Select Seo Tech',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#seo_admin_ids').select2({
                    placeholder: 'Select Seo Admin',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#content_writers_ids').select2({
                    placeholder: 'Select content Writers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
