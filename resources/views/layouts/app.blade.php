<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- @vite([
    'resources/css/remixicon.css',
    'resources/css/lib/bootstrap.min.css',
    'resources/css/lib/apexcharts.css',
    'resources/css/lib/dataTables.min.css',
    'resources/css/lib/editor-katex.min.css',
    'resources/css/lib/editor.atom-one-dark.min.css',
    'resources/css/lib/editor.quill.snow.css',
    'resources/css/lib/flatpickr.min.css',
    'resources/css/lib/full-calendar.css',
    'resources/css/lib/jquery-jvectormap-2.0.5.css',
    'resources/css/lib/magnific-popup.css',
    'resources/css/lib/slick.css',
    'resources/css/lib/prism.css',
    'resources/css/lib/file-upload.css',
    'resources/css/lib/audioplayer.css',
    'resources/css/style.css'
    ]) --}}

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
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-ui.min.js') }}"></script>

</head>

<body class="font-sans antialiased">
    @include('layouts.navigation')

    <main class="dashboard-main">
        <div class="navbar-header">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <button type="button" class="sidebar-toggle">
                            <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                            <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                        </button>
                        <button type="button" class="sidebar-mobile-toggle">
                            <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                        </button>
                        <div id="live-time"></div>
                        <script>
                            function updateTime() {
                                const options = {
                                    timeZone: 'Asia/Dubai',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                };
                                const currentTime = new Date().toLocaleTimeString('en-US', options);
                                document.getElementById('live-time').innerText = "CRM TIME:  " + currentTime;
                            }

                            setInterval(updateTime, 1000);

                            updateTime();
                        </script>

                    </div>
                </div>
                <div class="col-auto">

                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div id="notification-count" class="text-danger fs-7 fw-bold"></div>
                        <button type="button" data-theme-toggle
                            class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>


                        <div class="dropdown">
                            <button
                                class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                                type="button" data-bs-toggle="dropdown">
                                <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                                <div
                                    class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                                    </div>
                                    <span id="user-noti-count"
                                        class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center"></span>
                                </div>

                                <div class="max-h-400-px overflow-y-auto scroll-sm pe-4" id="notification-list"></div>

                                <script>
                                    $(document).ready(function() {
                                        fetchNotifications();
                                        fetchUnreadCount();

                                        // Fetch notifications every 1 minute
                                        setInterval(fetchNotifications, 60000);
                                        setInterval(fetchUnreadCount, 60000);

                                        // Fetch unread notifications
                                        function fetchNotifications() {
                                            $.ajax({
                                                url: "{{ route('notifications.fetch') }}",
                                                type: "GET",
                                                success: function(response) {
                                                    let html = "";
                                                    if (response.notifications.length === 0) {
                                                        html = '<p class="text-center text-muted">No new notifications</p>';
                                                    } else {
                                                        response.notifications.forEach(function(notification, index) {
                                                            if (index === 0) {
                                                                showBrowserNotification(notification.data.title,
                                                                    notification.data.message, notification.id);
                                                            }
                                                            html += `
                                                                <a href="javascript:void(0)"
                                                                   class="px-24 notification-item position-relative py-12 d-flex align-items-start gap-3 mb-2 justify-content-between"
                                                                   data-id="${notification.id}">
                                                                    <div class="text-black d-flex align-items-center gap-3">
                                                                        <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                                            <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                                                                        </span>
                                                                        <div>
                                                                            <h6 class="text-md fw-semibold mb-4">${notification.data.title}</h6>
                                                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">${notification.data.message}</p>
                                                                        </div>
                                                                    </div>
                                                                    <span class="text-sm text-secondary-light flex-shrink-0">
                                                                        ${timeAgo(notification.created_at)}
                                                                    </span>
                                                                </a>
                                                            `;
                                                        });
                                                    }
                                                    $("#notification-list").html(html);
                                                }
                                            });
                                        }

                                        function showBrowserNotification(title, message, notificationId) {
                                            let lastNotificationId = localStorage.getItem("lastNotificationId");

                                            // Check if this notification was already shown
                                            if (lastNotificationId == notificationId) {
                                                return; // Do not show the same notification again
                                            }

                                            // Store the new notification ID
                                            localStorage.setItem("lastNotificationId", notificationId);

                                            if (Notification.permission === "granted") {
                                                new Notification(title, {
                                                    body: message,
                                                    icon: "/images/notification-icon.png" // Replace with your notification icon
                                                });
                                            } else if (Notification.permission !== "denied") {
                                                Notification.requestPermission().then(permission => {
                                                    if (permission === "granted") {
                                                        new Notification(title, {
                                                            body: message,
                                                            icon: "/images/notification-icon.png"
                                                        });
                                                    }
                                                });
                                            }
                                        }

                                        // Mark as Read when clicked
                                        $(document).on("click", ".notification-item", function() {
                                            let notificationId = $(this).data("id");
                                            if (!notificationId) return;

                                            $.ajax({
                                                url: "{{ route('notifications.read', ':id') }}".replace(':id', notificationId),
                                                type: "POST",
                                                data: {
                                                    _token: "{{ csrf_token() }}"
                                                },
                                                success: function() {
                                                    fetchNotifications();
                                                    fetchUnreadCount();
                                                }
                                            });
                                        });

                                        // Fetch unread notification count for navbar
                                        function fetchUnreadCount() {
                                            $.ajax({
                                                url: "{{ route('notifications.unread.count') }}",
                                                type: "GET",
                                                success: function(response) {
                                                    if (response.count > 0) {
                                                        $('.has-indicator').addClass("animate-bell bg-danger-subtle");
                                                        $('.has-indicator').find("iconify-icon").removeClass("text-primary-light")
                                                            .addClass(
                                                                "text-danger-main");

                                                        if (!$('.has-indicator').find(".notification-dot").length) {
                                                            $('.has-indicator').append(`<span class="notification-dot"></span>`);
                                                        }
                                                        $("#user-noti-count").text(response.count);
                                                    } else {
                                                        // Remove animation, revert color, and remove dot
                                                        $('.has-indicator').removeClass("animate-bell bg-danger-subtle");
                                                        $('.has-indicator').find("iconify-icon").removeClass("text-danger-main")
                                                            .addClass(
                                                                "text-primary-light");
                                                        $('.has-indicator').find(".notification-dot").remove();
                                                        $("#user-noti-count").text('0');
                                                    }
                                                }

                                            });
                                        }

                                        // Function to convert timestamps to "x minutes ago"
                                        function timeAgo(time) {
                                            let date = new Date(time);
                                            let now = new Date();
                                            let diff = Math.floor((now - date) / 1000);

                                            if (diff < 60) return diff + " seconds ago";
                                            else if (diff < 3600) return Math.floor(diff / 60) + " minutes ago";
                                            else if (diff < 86400) return Math.floor(diff / 3600) + " hours ago";
                                            else return Math.floor(diff / 86400) + " days ago";
                                        }
                                    });
                                </script>

                            </div>
                        </div><!-- Notification dropdown end -->

                        <div class="dropdown">
                            <button class="d-flex justify-content-center align-items-center rounded-circle"
                                type="button" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->img ? asset('storage/' . auth()->user()->img) : asset('/images/user.png') }}"
                                    alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-sm">
                                <div
                                    class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-primary-light fw-semibold mb-2">
                                            {{ auth()->user()->name }}
                                        </h6>
                                        <span class="text-secondary-light fw-medium text-sm">
                                            {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                                        </span>
                                    </div>
                                    <button type="button" class="hover-text-danger">
                                        <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                                    </button>
                                </div>
                                <ul class="to-top-list">
                                    <li>
                                        <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                            href="{{ route('profile.edit') }}">
                                            <iconify-icon icon="solar:user-linear"
                                                class="icon text-xl"></iconify-icon>
                                            My Profile</a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3">
                                                <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                                                Log Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- Profile dropdown end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="#">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </a>
                    </li>
                </ul>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('dashboard') }}"
                            class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </li>
                </ul>
            </div>
            {{ $slot }}
        </div>
        <footer class="d-footer">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <p class="mb-0">© 2025 10X Digital. All Rights Reserved.</p>
                </div>
                <div class="col-auto">
                    <p class="mb-0">Made by <a href="https://10xdigital.ae" class="text-primary-600">10X
                            Digital</a></p>
                </div>
            </div>
        </footer>
    </main>

    {{-- @vite([
    'resources/js/lib/jquery-3.7.1.min.js',
    'resources/js/lib/bootstrap.bundle.min.js',
    'resources/js/lib/apexcharts.min.js',
    'resources/js/lib/dataTables.min.js',
    'resources/js/lib/iconify-icon.min.js',
    'resources/js/lib/jquery-ui.min.js',
    'resources/js/lib/jquery-jvectormap-2.0.5.min.js',
    'resources/js/lib/jquery-jvectormap-world-mill-en.js',
    'resources/js/lib/magnifc-popup.min.js',
    'resources/js/lib/slick.min.js',
    'resources/js/lib/prism.js',
    'resources/js/lib/file-upload.js',
    'resources/js/lib/audioplayer.js',
    'resources/js/app.js',
    'resources/js/homeOneChart.js'
    ]) --}}


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
    <!-- jQuery & Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (Session::has('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>
    <script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
