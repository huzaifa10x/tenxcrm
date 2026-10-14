@once
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}?v={{ @filemtime(base_path('css/attendance.css')) }}">
    <script src="{{ asset('js/attendance.js') }}?v={{ @filemtime(base_path('js/attendance.js')) }}"></script>
@endonce
