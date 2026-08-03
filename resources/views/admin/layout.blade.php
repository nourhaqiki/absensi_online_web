<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Absensi Online')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f7fb;
            --sidebar: #0f172a;
            --accent: #2563eb;
            --accent-2: #60a5fa;
            --card: #ffffff;
            --muted: #64748b;
            --border: #e2e8f0;
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eef4ff 0%, var(--bg) 100%);
            color: #0f172a;
        }
        .app-shell {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 270px;
            background: var(--sidebar);
            color: #fff;
            padding: 24px 18px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .brand-badge {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            font-weight: 700;
            font-size: 18px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all .2s ease;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .main {
            flex: 1;
            padding: 24px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .eyebrow {
            margin: 0;
            font-size: 12px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 700;
        }
        .card-panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .menu-card {
            display: block;
            padding: 18px;
            text-decoration: none;
            color: #0f172a;
            background: linear-gradient(135deg, #fff 0%, #f8fbff 100%);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
        }
        .menu-card .icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            margin-bottom: 12px;
            background: rgba(37, 99, 235, 0.1);
            color: var(--accent);
            font-size: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .stat-card { padding: 16px 18px; }
        .stat-label { color: var(--muted); font-size: 13px; }
        .stat-value { font-size: 24px; font-weight: 700; }
        .table thead th { background: #f8fafc; }
        @media (max-width: 992px) {
            .menu-grid, .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            .app-shell { display: block; }
            .sidebar { width: 100%; }
            .menu-grid, .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-badge">A</div>
            <div>
                <strong>Absensi</strong><br>
                <small class="text-white-50">
                        Login as: {{ session('admin_username', 'admin') }}
                </small>
            </div>
        </div>
        <nav>
            <a class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a class="menu-item {{ request()->routeIs('admin.employees') ? 'active' : '' }}" href="{{ route('admin.employees') }}">👥 Pegawai</a>
            <a class="menu-item {{ request()->routeIs('admin.locations') ? 'active' : '' }}" href="{{ route('admin.locations') }}">📍 Lokasi</a>
            <a class="menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">📈 Laporan</a>
            <a class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">⚙️ Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="btn btn-outline-light btn-sm w-100 rounded-3">Logout</button>
            </form>
        </nav>
    </aside>

        <main class="container-fluid px-3 ms-sm-auto px-md-4 pt-4">
            @yield('content')  {{-- atau @yield('content') ? --}}
        </main>

</div>

@stack('scripts')
</body>
</html>
