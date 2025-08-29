<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercises</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/exercises.css') }}">
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
            <a class="active" href="{{ route('admin.exercises') }}">Exercises</a>
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
            <div class="page-title"><h1>Exercises</h1></div>
        </div>

        <div class="page">
            <div class="add-button-container">
                <button class="btn green" type="button" onclick="toggleAddForm()">
                    <span id="addButtonText">+ Add New Exercise</span>
                </button>
                <a href="{{ route('admin.exercises.trashed') }}" class="btn gray" style="text-decoration: none; display: inline-block;">
                    🗑️ View Deleted Exercises
                </a>
            </div>

            <div class="card add-form-container" id="addFormContainer" style="margin-bottom:14px">
                <div class="toolbar" style="margin-bottom:8px"><div class="muted">Add New Exercise</div></div>
                <form class="form-inline" action="{{ route('admin.exercises.store') }}" method="post" enctype="multipart/form-data">
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
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="" disabled selected>Choose category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Image</label>
                        <input type="file" name="image_path" accept="image/*" />
                    </div>
                    <div>
                        <button class="btn" type="submit">Save</button>
                    </div>
                    <div>
                        <button class="btn gray" type="button" onclick="toggleAddForm()">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:52px">No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exercises as $index => $exercise)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exercise->name }}</td>
                            <td>
                                @if($exercise->category)
                                    <span class="pill">{{ $exercise->category->name }}</span>
                                @else
                                    <span class="pill no-category">No Category</span>
                                @endif
                            </td>
                            <td>{{ $exercise->description }}</td>
                            <td>
                                @if($exercise->image_path)
                                    <img src="{{ asset('imgs/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="exercise-image" />
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn small gray" type="button" onclick="toggleEdit({{ $exercise->id }})">Edit</button>
                                    <form action="{{ route('admin.exercises.delete', $exercise) }}" method="post" onsubmit="return confirm('Delete this exercise?')">
                                        @csrf
                                        <button class="btn small red" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="edit-row-{{ $exercise->id }}" style="display:none;background:#f8fafc">
                            <td colspan="6">
                                <form class="form-inline" action="{{ route('admin.exercises.update', $exercise) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="field">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{ $exercise->name }}" required />
                                    </div>
                                    <div class="field" style="flex:1;min-width:320px">
                                        <label>Description</label>
                                        <input type="text" name="description" value="{{ $exercise->description }}" required />
                                    </div>
                                    <div class="field">
                                        <label>Category</label>
                                        <select name="category_id" required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $exercise->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Image</label>
                                        <input type="file" name="image_path" accept="image/*" />
                                    </div>
                                    <div>
                                        <button class="btn" type="submit">Save</button>
                                    </div>
                                    <div>
                                        <button class="btn gray" type="button" onclick="toggleEdit({{ $exercise->id }})">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="muted">No exercises found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/exercises.js') }}"></script>
</body>
</html>


