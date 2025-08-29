<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Exercise Levels</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <style>
        .toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        .table{width:100%;border-collapse:separate;border-spacing:0;border:1px solid var(--border);background:#fff;border-radius:12px;overflow:hidden}
        .table th,.table td{padding:10px 12px;border-bottom:1px solid var(--border);text-align:left}
        .table th{background:#f8fafc;color:#475569;font-weight:700}
        .table tr:last-child td{border-bottom:0}
        .pill{display:inline-block;padding:2px 8px;border-radius:999px;background:#ecfeff;color:#0e7490;font-size:12px}
        .actions{display:flex;gap:8px}
        .btn.small{padding:6px 10px;font-size:12px;border-radius:6px}
        .btn.gray{background:#64748b}
        .btn.red{background:#ef4444;color:#fff}
        .btn.blue{background:#3b82f6;color:#fff}

    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sb-header">
            <div style="width:32px;height:32px;border-radius:8px;background:#a60000;display:grid;place-items:center">🏋️</div>
            <div class="brand">Home Workout</div>
        </div>
        <nav class="menu">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.exercises') }}">Exercises</a>
            <a href="{{ route('admin.categories') }}">Categories</a>
            <a href="{{ route('admin.plans') }}">Plans</a>
            <a class="active" href="{{ route('admin.exercise-levels') }}">Exercise Levels</a>
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
            <div class="page-title"><h1>Deleted Exercise Levels</h1></div>
        </div>

        <div class="page">
            <!-- Back to Exercise Levels Button -->
            <div class="add-button-container">
                <a href="{{ route('admin.exercise-levels') }}" class="btn gray" style="text-decoration: none; display: inline-block;">
                    ← Back to Exercise Levels
                </a>
            </div>

            <!-- Success and Error Messages -->
            @if(session('success'))
                <div class="alert success" style="margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert error" style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>{{ session('error') }}</span>
                        @if(session('show_cancel_button'))
                            <a href="{{ route('admin.exercise_levels.trashed') }}" class="btn small gray" style="text-decoration: none;">
                                Cancel
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:52px">No.</th>
                            <th>Exercise</th>
                            <th>Level</th>
                            <th>Calories</th>
                            <th>Reps</th>
                            <th>Timer</th>
                            <th>Deleted At</th>
                            <th style="width:200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exerciseLevels as $index => $exerciseLevel)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($exerciseLevel->exercise)
                                    {{ $exerciseLevel->exercise->name }}
                                @else
                                    <span style="color:#991b1b">Exercise Deleted</span>
                                @endif
                            </td>
                            <td>
                                @if($exerciseLevel->level)
                                    <span class="pill">{{ $exerciseLevel->level->name }}</span>
                                @else
                                    <span class="pill" style="background:#fee2e2;color:#991b1b">No Level</span>
                                @endif
                            </td>
                            <td>{{ $exerciseLevel->calories }}</td>
                            <td>{{ $exerciseLevel->number_of_rips ?? '—' }}</td>
                            <td>{{ $exerciseLevel->timer ?? '—' }}s</td>
                            <td>{{ $exerciseLevel->deleted_at ? $exerciseLevel->deleted_at->format('Y-m-d H:i:s') : '—' }}</td>
                            <td>
                                <div class="actions">
                                    <form action="{{ route('admin.exercise-levels.restore', $exerciseLevel->id) }}" method="post">
                                        @csrf
                                        <button class="btn small blue" type="submit" onclick="return confirm('Restore this exercise level?')">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.exercise-levels.force-delete', $exerciseLevel->id) }}" method="post">
                                        @csrf
                                        <button class="btn small red" type="submit" onclick="return confirm('Permanently delete this exercise level? This action cannot be undone!')">Delete Forever</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="muted">No deleted exercise levels found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
