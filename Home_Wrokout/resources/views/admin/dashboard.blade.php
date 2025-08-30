<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sb-header">
                <div
                    style="width:32px;height:32px;border-radius:8px;background:#a60000;display:grid;place-items:center">
                    🏋️</div>
                <div class="brand">Home Workout</div>
            </div>
            <nav class="menu">
                <a class="active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.exercises') }}">Exercises</a>
                <a href="{{ route('admin.categories') }}">Categories</a>
                <a href="{{ route('admin.plans') }}">Plans</a>
                <a href="{{ route('admin.exercise-levels') }}">Exercise Levels</a>
                <a href="{{ route('admin.users') }}">Users</a>
            </nav>
            <div class="logout-section">
                <form action="{{ route('admin.logout') }}" method="post" style="margin:0">
                    @csrf
                    <button type="submit" class="logout-btn">🚪 Logout</button>
                </form>
            </div>
        </aside>


        <main class="main">
            <div class="topbar">
                <div></div>
            </div>

            <div class="page">
                <div class="page-title">
                    <h1>Training Analytics</h1>
                    <span class="muted">A quick look at your home workout platform</span>
                </div>

                <section class="grid stats" style="margin-bottom:14px">
                    <div class="card" style="grid-column: span 6">
                        <div class="stat-icon">📈</div>
                        <div>
                            <div class="muted">Total Users</div>
                            <div id="users-count" style="font-weight:700;font-size:18px">0</div>
                        </div>
                    </div>
                    <div class="card" style="grid-column: span 6">
                        <div class="stat-icon" style="background:#a60000">🗂️</div>
                        <div>
                            <div class="muted">Active Plans</div>
                            <div id="plans-count" style="font-weight:700;font-size:18px">0</div>
                        </div>
                    </div>
                </section>

                <section class="grid">
                    <div class="card chart-card" style="grid-column: span 12">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <div style="font-weight:700">Users by Level</div>
                            <div class="muted" id="levels-info">Loading levels...</div>
                        </div>
                        <canvas id="users-by-level-chart" height="180"
                            style="width:100%;max-width:520px;display:block;margin:0 auto"></canvas>
                        <div class="legend" id="chart-legend">
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>
