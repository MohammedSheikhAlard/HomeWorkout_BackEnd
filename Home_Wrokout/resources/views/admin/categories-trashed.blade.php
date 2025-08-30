<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Categories</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <style>
        .img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px
        }

        .btn.blue {
            background: #3b82f6;
            color: #fff
        }
    </style>
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
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.exercises') }}">Exercises</a>
                <a class="active" href="{{ route('admin.categories') }}">Categories</a>
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
                <div class="page-title">
                    <h1>Deleted Categories</h1>
                </div>
            </div>

            <div class="page">
                <div class="add-button-container">
                    <a href="{{ route('admin.categories') }}" class="btn gray"
                        style="text-decoration: none; display: inline-block;">
                        ← Back to Categories
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert success" style="margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert error" style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ session('error') }}</span>
                            @if (session('show_cancel_button'))
                                <a href="{{ route('admin.categories.trashed') }}" class="btn small gray"
                                    style="text-decoration: none;">
                                    Cancel
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="toolbar">
                        <div class="muted">Deleted Categories (<span
                                id="categoriesCount">{{ $categories->count() }}</span> total)</div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:52px">No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Deleted At</th>
                                <th style="width:200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        @if ($category->image_path)
                                            <img class="img" src="{{ asset('imgs/' . $category->image_path) }}"
                                                alt="{{ $category->name }}" />
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $category->deleted_at ? $category->deleted_at->format('Y-m-d H:i:s') : '—' }}
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <form action="{{ route('admin.categories.restore', $category->id) }}"
                                                method="post">
                                                @csrf
                                                <button class="btn small blue" type="submit"
                                                    onclick="return confirm('Restore this category?')">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.categories.force-delete', $category->id) }}"
                                                method="post">
                                                @csrf
                                                <button class="btn small red" type="submit"
                                                    onclick="return confirm('Permanently delete this category? This action cannot be undone!')">Delete
                                                    Forever</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="muted">No deleted categories found.</td>
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
