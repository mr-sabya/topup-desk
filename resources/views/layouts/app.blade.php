<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
    <title>Admin Mobile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-app: #007aff;
        }

        /* iOS Blue */
        body {
            background-color: #f2f2f7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica;
            padding-bottom: 90px;
            /* Space for bottom nav */
            padding-top: 60px;
            /* Space for top nav */
        }

        /* Top Bar */
        /* Premium Header Style */
        .top-nav {
            position: fixed;
            top: 0;
            width: 100%;
            height: 65px;
            background: rgba(255, 255, 255, 0.85);
            /* Glass effect */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.1);
            z-index: 1050;
        }

        /* Dropdown Animation and Styling */
        .dropdown-menu {
            --bs-dropdown-link-active-bg: #f8f9fa;
            --bs-dropdown-link-active-color: #000;
        }

        .dropdown-item {
            font-size: 14px;
            transition: all 0.2s;
        }

        .dropdown-item:active {
            background-color: #f2f2f7;
        }

        /* Animation */
        .animate__fadeIn {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bottom Tab Bar */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 75px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-top: 0.5px solid #d1d1d6;
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            padding-top: 10px;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .tab-item {
            text-decoration: none;
            color: #8e8e93;
            text-align: center;
            font-size: 10px;
            flex: 1;
        }

        .tab-item i {
            font-size: 24px;
            display: block;
            margin-bottom: 2px;
        }

        .tab-item.active {
            color: var(--primary-app);
        }

        /* Floating Action Button (FAB) */
        .fab {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--primary-app);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 999;
            border: none;
        }

        /* App Cards */
        .app-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 10px;
            padding: 15px;
            border: none;
            display: flex;
            align-items: center;
        }

        /* Pull-up Form (Bottom Sheet) */
        .offcanvas-bottom {
            height: 80% !important;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
    </style>
</head>

<body>

    <nav class="top-nav px-3 d-flex align-items-center justify-content-between">
        <!-- Page Title -->
        <span class="fw-bold fs-5 text-dark m-0">{{ $title ?? 'Dashboard' }}</span>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=007aff&color=fff"
                    class="rounded-circle border shadow-sm"
                    width="36" height="36">
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 animate__animated animate__fadeIn" style="min-width: 200px;">
                <li class="px-3 py-2 border-bottom mb-1">
                    <small class="text-muted d-block" style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px;">Administrator</small>
                    <span class="fw-bold d-block text-dark">{{ auth()->user()->name ?? 'Admin User' }}</span>
                </li>

                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center" href="#">
                        <i class="bi bi-person-circle me-3 text-secondary"></i> My Profile
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center" href="#">
                        <i class="bi bi-shield-lock me-3 text-secondary"></i> Security
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider opacity-50">
                </li>

                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center text-danger fw-bold" href="{{ route('logout') }}">
                        <i class="bi bi-box-arrow-left me-3"></i> Log Out
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container py-3">
        {{ $slot }}
    </main>

    <nav class="bottom-nav">
        <a href="/admin" class="tab-item {{ request()->is('admin') ? 'active' : '' }}" wire:navigate>
            <i class="bi bi-grid-1x2"></i><span>Home</span>
        </a>
        <a href="/admin/categories" class="tab-item {{ request()->is('admin/categories*') ? 'active' : '' }}" wire:navigate>
            <i class="bi bi-tag"></i><span>Categories</span>
        </a>
        <a href="/admin/providers" class="tab-item {{ request()->is('admin/providers*') ? 'active' : '' }}" wire:navigate>
            <i class="bi bi-person-badge"></i><span>Providers</span>
        </a>
        <a href="#" class="tab-item">
            <i class="bi bi-gear"></i><span>Settings</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>