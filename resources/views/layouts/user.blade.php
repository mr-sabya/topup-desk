<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
    <title>{{ $title ?? 'EasyPay' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --bg-soft: #f8fafc;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        body {
            background: linear-gradient(180deg, #eef2ff, #f8fafc);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 100px;
            padding-top: 80px;
        }


        /* Top Bar */
        .user-header {
            position: fixed;
            top: 0;
            width: 100%;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .user-header img {
            height: 42px;
        }

        /* Bottom Tab Bar */
        .user-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 85px;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            padding-bottom: env(safe-area-inset-bottom);
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.08);
        }

        .user-nav {
            position: fixed;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 30px);
            max-width: 420px;
            height: 75px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 14px;
        }

        .user-nav-item {
            text-decoration: none;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            position: relative;
        }

        .user-nav-item i {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .user-nav-item:hover {
            color: var(--primary);
        }

        .user-nav-item.active {
            color: var(--primary);
        }

        /* CENTER HOME BUTTON */
        .user-nav-home {
            flex: 1.4;
            position: relative;
            top: -22px;
        }

        .home-btn {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.45);
            color: white;
            font-size: 28px;
            margin-bottom: 2px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .user-nav-home.active .home-btn {
            transform: scale(1.05);
            box-shadow: 0 12px 28px rgba(79, 70, 229, 0.6);
        }

        .user-nav-home:hover .home-btn {
            transform: translateY(-2px) scale(1.05);
        }

        .home-label {
            font-size: 12px;
            color: var(--primary);
            font-weight: 700;
            margin-top: 4px;
        }


        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            opacity: 0.95;
        }

        /* Cards */
        .card-app {
            background: white;
            border-radius: 22px;
            border: none;
            box-shadow: var(--card-shadow);
        }

        /* Inputs */
        .form-control {
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(79, 70, 229, 0.15);
        }

        /* Utility */
        .fw-600 {
            font-weight: 600;
        }

        .active-scale:active {
            transform: scale(0.97);
            transition: 0.1s;
        }

        .gradient-icon-bg {
            background: linear-gradient(135deg, #e0e7ff, #ecfeff);
            padding: 12px;
            border-radius: 50%;
        }
    </style>
    @livewireStyles
</head>

<body>

    <header class="user-header">
        <div class="d-flex align-items-center w-100">
            <!-- LOGO -->
            <img src="{{ asset('images/logo.png') }}" alt="EasyPay Logo" class="me-3">

            <h5 class="fw-bold m-0 text-white">{{ $title ?? 'EasyPay' }}</h5>

            <div class="ms-auto">
                <i class="bi bi-bell fs-4 text-white opacity-75"></i>
            </div>
        </div>
    </header>

    <main class="container py-3">
        @yield('content')
    </main>

    <nav class="user-nav">
        <a href="#" class="user-nav-item">
            <i class="bi bi-clock-history"></i>
            <span>History</span>
        </a>

        <a href="#" class="user-nav-item">
            <i class="bi bi-wallet2"></i>
            <span>Wallet</span>
        </a>

        <!-- CENTER HOME BUTTON -->
        <a href="/" class="user-nav-item user-nav-home {{ request()->is('/') ? 'active' : '' }}" wire:navigate>
            <div class="home-btn">
                <i class="bi bi-house-door-fill"></i>
            </div>
            <span class="home-label">Home</span>
        </a>

        <a href="#" class="user-nav-item">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>

        <a href="#" class="user-nav-item">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    </nav>

    <!-- The Maintenance Overlay (Hidden by default) -->
    <div id="maintenanceOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; z-index: 999999; flex-direction: column; align-items: center; justify-content: center; text-align: center; font-family: sans-serif;">
        <div style="width: 100px; height: 100px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 3.5A.5.5 0 0 1 6 4v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm5 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z" />
            </svg>
        </div>
        <h2 style="font-weight: 800; color: #1e293b;">Service Paused</h2>
        <p style="color: #64748b; max-width: 300px;">Our counter is temporarily closed for an update. Please wait a moment.</p>
        <div class="spinner-grow text-primary" role="status"></div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkStatus() {
            fetch('/app-status-check')
                .then(response => response.json())
                .then(data => {
                    const overlay = document.getElementById('maintenanceOverlay');

                    // If paused, show the overlay. If not, hide it.
                    // NO PAGE RELOAD HAPPENS HERE.
                    if (data.paused === true) {
                        overlay.style.display = 'flex';
                    } else {
                        overlay.style.display = 'none';
                    }
                })
                .catch(err => console.error("Check failed"));
        }

        // Run every 5 seconds
        setInterval(checkStatus, 5000);

        // Also run once immediately on page load
        checkStatus();
    </script>
    @livewireScripts
</body>

</html>