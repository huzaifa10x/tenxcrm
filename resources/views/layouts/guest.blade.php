<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>10X One | Login</title>
    <link rel="icon" href="{{ asset('/public/fav-icon.webp') }}" type="image/png" sizes="16x16">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --accent-soft: rgba(25, 135, 84, 0.1);
        }

        body {
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .auth-section {
            min-height: 100vh;
            display: flex;
        }

        /* Modern Left Side - Mesh Gradient & Glassmorphism */
        .auth-left {
            flex: 1.2;
            background: var(--primary-gradient);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Subtle animated background blob */
        .auth-left::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(25, 135, 84, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            top: -10%;
            left: -10%;
            border-radius: 50%;
            filter: blur(50px);
        }

        .auth-left-content {
            z-index: 10;
            text-align: center;
            padding: 40px;
        }

        .brand-logo-container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .auth-left-content h1 {
            color: #ffffff;
            font-weight: 700;
            margin-top: 24px;
            font-size: 2.5rem;
            letter-spacing: -1px;
        }

        /* Right Side - Clean & Focused */
        .auth-right {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .auth-right-container {
            width: 100%;
            max-width: 480px;
            padding: 40px;
        }

        /* Responsive Tweaks */
        @media (max-width: 992px) {
            .auth-left { display: none; }
            .auth-right { flex: 1; width: 100%; }
        }

        /* Transitions */
        .img-fluid {
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .img-fluid:hover {
            transform: scale(1.05);
        }
    </style>

    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
</head>

<body class="antialiased">
    <section class="auth-section">
        <div class="auth-left d-lg-flex d-none">
            <div class="auth-left-content">
                <div class="brand-logo-container">
                    <img src="{{ asset('/public/light-logo.png') }}" 
                         class="img-fluid" 
                         style="max-height: 80px;" 
                         alt="10X One Logo">
                </div>
                <h1>Empowering Your <br><span style="color: #a8e323;">Workflow.</span></h1>
                <p class="text-white opacity-50 mt-3 fs-5 fw-light">The all-in-one ecosystem for productivity.</p>
            </div>
        </div>

        <div class="auth-right py-32">
            <div class="auth-right-container">
                <div class="d-lg-none text-center mb-4">
                    <img src="{{ asset('/public/light-logo.png') }}" height="40" alt="Logo">
                </div>
                
                {{ $slot }}
            </div>
        </div>
    </section>

    <script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/lib/iconify-icon.min.js') }}"></script>
    
    <script>
        // Password Toggle Logic
        function initializePasswordToggle(toggleSelector) {
            $(document).on('click', toggleSelector, function() {
                $(this).toggleClass("ri-eye-off-line ri-eye-line");
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
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>