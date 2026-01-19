<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
    <title>{{ $title ?? 'EasyPay' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --user-primary: #4f46e5;
            --bg-soft: #f8fafc;
        }

        body {
            background-color: var(--bg-soft);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 100px;
            padding-top: 70px;
        }

        /* Top Bar */
        .user-header {
            position: fixed;
            top: 0;
            width: 100%;
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        /* Bottom Tab Bar */
        .user-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 80px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .user-nav-item {
            text-decoration: none;
            color: #94a3b8;
            text-align: center;
            font-size: 11px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: 600;
        }

        .user-nav-item i {
            font-size: 24px;
            margin-bottom: 2px;
        }

        .user-nav-item.active {
            color: var(--user-primary);
        }

        /* General App Styles */
        .btn-primary {
            background-color: var(--user-primary);
            border: none;
            padding: 12px;
            border-radius: 14px;
            font-weight: 600;
        }

        .card-app {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            background: #fcfdfe;
        }

        .form-control:focus {
            border-color: var(--user-primary);
            box-shadow: none;
        }
    </style>
</head>

<body>

    <header class="user-header">
        <div class="d-flex align-items-center w-100">
            <h5 class="fw-bold m-0 text-dark">{{ $title ?? 'EasyPay' }}</h5>
            <div class="ms-auto">
                <i class="bi bi-bell fs-4 text-secondary"></i>
            </div>
        </div>
    </header>

    <main class="container py-2">
        @yield('content')
    </main>

    <nav class="user-nav">
        <a href="/" class="user-nav-item {{ request()->is('/') ? 'active' : '' }}" wire:navigate>
            <i class="bi bi-house-door{{ request()->is('/') ? '-fill' : '' }}"></i>
            <span>Home</span>
        </a>
        <a href="#" class="user-nav-item">
            <i class="bi bi-clock-history"></i>
            <span>History</span>
        </a>
        <a href="#" class="user-nav-item">
            <i class="bi bi-wallet2"></i>
            <span>Wallet</span>
        </a>
        <a href="#" class="user-nav-item">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>