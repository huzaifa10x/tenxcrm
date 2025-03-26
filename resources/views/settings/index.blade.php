<x-app-layout>
    @if (auth()->user()->hasRole('ADMIN'))
        <x-slot name="header">
            {{ __('System Setting') }}
        </x-slot>
        <form id="settingsForm">
            @csrf
            <div class="form-group">
                <label>Office Open Time:</label>
                <input type="time" name="office_open_time" value="{{ $settings->office_open_time ?? '' }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Office Close Time:</label>
                <input type="time" name="office_close_time" value="{{ $settings->office_close_time ?? '' }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Late Threshold (Minutes After Office Open Time e.g 15 min):</label>
                <input type="number" name="late_threshold" value="{{ $settings->late_threshold ?? '15' }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Late Deduction Threshold (Number of late arrivals that result in one deduction):</label>
                <input type="number" name="late_deduction_threshold"
                    value="{{ $settings->late_deduction_threshold ?? '3' }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Half-Day Deduction Threshold (Number of half-days that result in one deduction):</label>
                <input type="number" name="half_day_deduction_threshold"
                    value="{{ $settings->half_day_deduction_threshold ?? '3' }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Half-Day Threshold (Late after this = Half Day):</label>
                <input type="time" name="half_day_threshold" value="{{ $settings->half_day_threshold ?? '12:00' }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Break End Time:</label>
                <input type="time" name="break_end_time" value="{{ $settings->break_end_time ?? '14:00' }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Early Out Threshold (Punch out before this = Early Out):</label>
                <input type="time" name="early_out_threshold"
                    value="{{ $settings->early_out_threshold ?? ($settings->office_close_time ?? '') }}"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Holidays (Comma Separated Dates) e.g ["2025-03-23", "2025-04-01"]</label>
                <input type="text" name="holidays"
                    value="{{ isset($settings->holidays) ? implode(',', $settings->holidays) : '' }}"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary btn-sm my-10">Save Settings</button>
        </form>

        <div id="responseMessage"></div>
        <script>
            document.getElementById('settingsForm').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                fetch("{{ route('settings.update') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('responseMessage').innerHTML =
                            `<div class="alert alert-success">${data.success}</div>`;
                    })
                    .catch(error => {
                        document.getElementById('responseMessage').innerHTML =
                            `<div class="alert alert-success">${error}</div>`;
                    });
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
