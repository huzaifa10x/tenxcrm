<x-app-layout>
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
            <label>Holidays (Comma Separated Dates) e.g ["2025-03-23", "2025-04-01"]</label>
            <input type="text" name="holidays"
                value="{{ isset($settings->holidays) ? implode(',', $settings->holidays) : '' }}" class="form-control">
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
                    console.error('Error:', error);
                });
        });
    </script>
</x-app-layout>
