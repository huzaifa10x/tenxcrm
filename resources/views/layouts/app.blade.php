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
                        <form class="navbar-search">
                            <input type="text" name="search" placeholder="Search">
                            <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        </form>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <button type="button" data-theme-toggle
                            class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                        <div class="dropdown d-none d-sm-inline-block">
                            <button
                                class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                                type="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('/images/lang-flag.png') }}" alt="image"
                                    class="w-24 h-24 object-fit-cover rounded-circle">
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-sm">
                                <div
                                    class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language
                                        </h6>
                                    </div>
                                </div>

                                <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="english">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag1.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">English</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto" id="english">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="japan">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag2.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">Japan</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto" id="japan">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="france">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag3.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">France</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="france">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="germany">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag4.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">Germany</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="germany">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="korea">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag5.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">South Korea</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="korea">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="bangladesh">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag6.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">Bangladesh</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="bangladesh">
                                    </div>

                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="india">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag7.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">India</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="india">
                                    </div>
                                    <div
                                        class="form-check style-check d-flex align-items-center justify-content-between">
                                        <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                            for="canada">
                                            <span
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                                <img src="{{ asset('/images/flags/flag8.png') }}" alt=""
                                                    class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                                <span class="text-md fw-semibold mb-0">Canada</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input" type="radio" name="crypto"
                                            id="canada">
                                    </div>
                                </div>
                            </div>
                        </div><!-- Language dropdown end -->

                        <div class="dropdown">
                            <button
                                class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                                type="button" data-bs-toggle="dropdown">
                                <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                                <div
                                    class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
                                    </div>
                                    <span
                                        class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                                </div>

                                <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                                <img src="{{ asset('/images/notification/profile-3.png') }}"
                                                    alt="">
                                                <span
                                                    class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey!
                                                    there i’m...</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-sm text-secondary-light flex-shrink-0">12:30
                                                PM</span>
                                            <span
                                                class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                                <img src="{{ asset('/images/notification/profile-4.png') }}"
                                                    alt="">
                                                <span
                                                    class="w-8-px h-8-px  bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey!
                                                    there i’m...</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-sm text-secondary-light flex-shrink-0">12:30
                                                PM</span>
                                            <span
                                                class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">2</span>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                                <img src="{{ asset('/images/notification/profile-5.png') }}"
                                                    alt="">
                                                <span
                                                    class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey!
                                                    there i’m...</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-sm text-secondary-light flex-shrink-0">12:30
                                                PM</span>
                                            <span
                                                class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                                <img src="{{ asset('/images/notification/profile-6.png') }}"
                                                    alt="">
                                                <span
                                                    class="w-8-px h-8-px bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey!
                                                    there i’m...</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-sm text-secondary-light flex-shrink-0">12:30
                                                PM</span>
                                            <span
                                                class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                                <img src="{{ asset('/images/notification/profile-7.png') }}"
                                                    alt="">
                                                <span
                                                    class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey!
                                                    there i’m...</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-sm text-secondary-light flex-shrink-0">12:30
                                                PM</span>
                                            <span
                                                class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                        </div>
                                    </a>

                                </div>
                                <div class="text-center py-12 px-16">
                                    <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See
                                        All Message</a>
                                </div>
                            </div>
                        </div><!-- Message dropdown end -->

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
                                    <span
                                        class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                                </div>

                                <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                <iconify-icon icon="bitcoin-icons:verify-outline"
                                                    class="icon text-xxl"></iconify-icon>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Congratulations</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-200-px">Your
                                                    profile has been Verified. Your
                                                    profile has been Verified</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                <img src="{{ asset('/images/notification/profile-1.png') }}"
                                                    alt="">
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Ronald Richards</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-200-px">You can
                                                    stitch between artboards</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                AM
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Arlene McCoy</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite
                                                    you to prototyping</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                <img src="{{ asset('/images/notification/profile-2.png') }}"
                                                    alt="">
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Annette Black</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite
                                                    you to prototyping</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                        <div
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span
                                                class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                DR
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">Darlene Robertson</h6>
                                                <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite
                                                    you to prototyping</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                    </a>
                                </div>

                                <div class="text-center py-12 px-16">
                                    <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See
                                        All Notification</a>
                                </div>

                            </div>
                        </div><!-- Notification dropdown end -->

                        <div class="dropdown">
                            <button class="d-flex justify-content-center align-items-center rounded-circle"
                                type="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('/images/user.png') }}" alt="image"
                                    class="w-40-px h-40-px object-fit-cover rounded-circle">
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-sm">
                                <div
                                    class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ 'username' }}
                                        </h6>
                                        <span class="text-secondary-light fw-medium text-sm">Admin</span>
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
                                                class="icon text-xl"></iconify-icon> My Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                            href="email.html">
                                            <iconify-icon icon="tabler:message-check"
                                                class="icon text-xl"></iconify-icon> Inbox</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                            href="company.html">
                                            <iconify-icon icon="icon-park-outline:setting-two"
                                                class="icon text-xl"></iconify-icon>
                                            Setting</a>
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

    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/lib/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/lib/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/lib/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-ui.min.js') }}"></script>
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
