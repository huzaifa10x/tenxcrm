<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>10X CRM | login 10x Crm</title>
    <link rel="icon" href="{{ asset('/public/fav-icon.webp') }}" type="image/png" sizes="16x16">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor.quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/full-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-jvectormap-2.0.5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/audioplayer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-ui.min.js') }}"></script>

</head>

<body class="font-sans text-gray-900 antialiased">
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img style="filter: hue-rotate(240deg);" src="{{ asset('/images/auth/auth-img.png') }}" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            {{ $slot }}
        </div>
    </section>
    <script>
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        initializePasswordToggle('.toggle-password');
    </script>

    <script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/lib/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/lib/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/lib/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('js/lib/magnifc-popup.min.js') }}"></script>
    <script src="{{ asset('js/lib/slick.min.js') }}"></script>
    <script src="{{ asset('js/lib/prism.js') }}"></script>
    <script src="{{ asset('js/lib/file-upload.js') }}"></script>
    <script src="{{ asset('js/lib/audioplayer.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/homeOneChart.js') }}"></script>
</body>

</html>
