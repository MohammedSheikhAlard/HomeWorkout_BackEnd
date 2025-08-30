<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
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
                    <h1>Categories</h1>
                </div>
            </div>

            <div class="page">
                @if (session('success'))
                    <div class="alert success" style="margin-bottom: 16px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert error" style="margin-bottom: 16px;">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert error" style="margin-bottom: 16px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="add-button-container">
                    <button class="btn green" type="button" onclick="toggleAddForm(this)">
                        <span>+ Add New Category</span>
                    </button>
                    <a href="{{ route('admin.categories.trashed') }}" class="btn gray"
                        style="text-decoration: none; display: inline-block;">
                        🗑️ View Deleted Categories
                    </a>
                </div>

                <!-- Add New Category Form (Hidden by default) -->
                <div class="card add-form-container" id="addFormContainer">
                    <div class="toolbar" style="margin-bottom:8px">
                        <div class="muted">Add New Category</div>
                    </div>
                    <form class="form-inline" action="{{ route('admin.categories.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="field">
                            <label>Name</label>
                            <input type="text" name="name" required />
                        </div>
                        <div class="field" style="flex:1;min-width:320px">
                            <label>Description</label>
                            <input type="text" name="description" required />
                        </div>
                        <div class="field">
                            <label>Image</label>
                            <input type="file" name="image_path" accept="image/*" />
                            <small class="muted">Optional: Upload an image for this category</small>
                        </div>
                        <div>
                            <button class="btn" type="submit">Save</button>
                        </div>
                        <div>
                            <button class="btn gray" type="button" onclick="toggleAddForm(this)">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="toolbar">
                        <div class="muted">All Categories (<span
                                id="categoriesCount">{{ $categories->count() }}</span> total)</div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:52px">No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
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
                                    <td>
                                        <div class="actions">
                                            <button class="btn small gray" type="button"
                                                onclick="toggleEdit({{ $category->id }})">Edit</button>
                                            <form action="{{ route('admin.categories.delete', $category) }}"
                                                method="post" style="display:inline">
                                                @csrf
                                                <button class="btn small red" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="edit-row-{{ $category->id }}" style="display:none;background:#f8fafc">
                                    <td colspan="5">
                                        <form class="form-inline"
                                            action="{{ route('admin.categories.update', $category) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="field">
                                                <label>Name</label>
                                                <input type="text" name="name" value="{{ $category->name }}"
                                                    required />
                                            </div>
                                            <div class="field" style="flex:1;min-width:320px">
                                                <label>Description</label>
                                                <input type="text" name="description"
                                                    value="{{ $category->description }}" required />
                                            </div>
                                            <div class="field">
                                                <label>Image</label>
                                                <input type="file" name="image_path" accept="image/*" />
                                                @if ($category->image_path)
                                                    <div style="margin-top: 8px;">
                                                        <small class="muted">Current:
                                                            {{ basename($category->image_path) }}</small>
                                                        <br>
                                                        <img class="img"
                                                            src="{{ asset('imgs/' . $category->image_path) }}"
                                                            alt="{{ $category->name }}"
                                                            style="width: 32px; height: 32px;" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <button class="btn" type="submit">Save</button>
                                            </div>
                                            <div>
                                                <button class="btn gray" type="button"
                                                    onclick="toggleEdit({{ $category->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="muted">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    </main>
    </div>

    <script src="{{ asset('js/tables.js') }}"></script>
</body>

</html>
