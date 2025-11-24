<x-app-layout>
    <x-slot name="header">
        {{ __('Create Website Project') }}
    </x-slot>
    @if (auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('ADMIN'))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Website Project Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('website-projects.store') }}" method="POST">
                    @csrf

                    <div class="row gy-3">
                        <!-- Project Selection -->
                        @php
                            $selectedProjectId = request('project_id'); // e.g. ?project_id=1
                        @endphp

                        <div class="col-lg-6">
                            <label class="form-label" for="project_id">Project *</label>
                            <select name="project_id" id="project_id" class="form-control" required>
                                <option value="">Select Project</option>

                                @foreach ($projects as $project)
                                    @php
                                        $isSelected = $selectedProjectId && $selectedProjectId == $project->id;
                                    @endphp

                                    <option value="{{ $project->id }}"
                                        @if ($isSelected) selected @endif
                                        @if ($selectedProjectId && !$isSelected) disabled @endif>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('project_id')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Platform -->
                        <div class="col-lg-6">
                            <label class="form-label" for="platform">Platform *</label>
                            <select name="platform" id="platform" class="form-control" required>
                                <option value="">Select Platform</option>
                                <option value="WordPress">WordPress</option>
                                <option value="Next.js">Next.js</option>
                            </select>
                            @error('platform')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">WordPress: Auto-assign to Fabiha | Next.js: Auto-assign to
                                Daniyal</small>
                        </div>

                        <!-- Developer Assigned -->
                        <div class="col-lg-6">
                            <label class="form-label" for="developer_assigned">Developer Assigned</label>
                            <select name="developer_assigned" id="developer_assigned" class="form-control">
                                <option value="">Auto-assign based on platform</option>
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                @endforeach
                            </select>
                            @error('developer_assigned')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Lead Developer -->
                        <div class="col-lg-6">
                            <label class="form-label" for="lead_developer">Lead Developer</label>
                            <select name="lead_developer" id="lead_developer" class="form-control">
                                <option value="">Auto-assign to Huzaifa</option>
                                @foreach ($leadDevelopers as $leadDev)
                                    <option value="{{ $leadDev->id }}">{{ $leadDev->name }}</option>
                                @endforeach
                            </select>
                            @error('lead_developer')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Has Current SEO -->
                        <div class="col-lg-6">
                            <label class="form-label">Website has Current SEO?</label>
                            <div class="gap-3 d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_seo" id="seo_yes"
                                        value="1">
                                    <label class="form-check-label" for="seo_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_seo" id="seo_no"
                                        value="0" checked>
                                    <label class="form-check-label" for="seo_no">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Has Current Google Ads -->
                        <div class="col-lg-6">
                            <label class="form-label">Website has Current Google Ads?</label>
                            <div class="gap-3 d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_google_ads"
                                        id="ads_yes" value="1">
                                    <label class="form-check-label" for="ads_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_google_ads"
                                        id="ads_no" value="0" checked>
                                    <label class="form-check-label" for="ads_no">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Target Go-Live Date -->
                        <div class="col-lg-6">
                            <label class="form-label" for="target_go_live_date">Target Go-Live Date</label>
                            <input type="date" name="target_go_live_date" id="target_go_live_date"
                                class="form-control">
                            @error('target_go_live_date')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-lg-12">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"></textarea>
                            @error('notes')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-lg-12">
                            <div class="gap-3 d-flex justify-content-end">
                                <a href="{{ route('website-projects.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Website Project</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
