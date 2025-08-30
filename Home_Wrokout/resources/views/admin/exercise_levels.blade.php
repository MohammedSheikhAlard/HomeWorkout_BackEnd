<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Levels</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/exercise-levels.css') }}">
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
                <div class="page-title">
                    <h1>Exercise Levels</h1>
                </div>
            </div>

            <div class="page">
                <div class="add-button-container">
                    <button class="btn green" type="button" onclick="toggleAddForm()">
                        <span id="addButtonText">+ Add New Exercise Level</span>
                    </button>
                    <a href="{{ route('admin.exercise-levels.trashed') }}" class="btn gray"
                        style="text-decoration: none; display: inline-block;">
                        🗑️ View Deleted Exercise Levels
                    </a>
                </div>

                <div class="card add-form-container" id="addFormContainer" style="margin-bottom:14px">
                    <div class="toolbar" style="margin-bottom:8px">
                        <div class="muted">Add New Exercise Level</div>
                    </div>
                    <form class="form-inline" action="{{ route('admin.exercise-levels.store') }}" method="post">
                        @csrf
                        <div class="field">
                            <label>Exercise</label>
                            <select name="exercise_id" required>
                                <option value="" disabled selected>Choose exercise</option>
                                @foreach ($exercises as $exercise)
                                    <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Level</label>
                            <select name="level_id" required>
                                <option value="" disabled selected>Choose level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Calories</label>
                            <input type="number" name="calories" min="1" required />
                        </div>
                        <div class="field">
                            <label>Reps</label>
                            <input type="number" name="number_of_rips" min="1" />
                        </div>
                        <div class="field">
                            <label>Timer (seconds)</label>
                            <input type="number" name="timer" min="1" value="30" />
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
                    <div class="toolbar">
                        <div class="muted">All Exercise Levels (<span
                                id="exerciseLevelsCount">{{ $exerciseLevels->count() }}</span> total)</div>
                        <div>
                            <select id="levelFilter" onchange="filterExerciseLevels()" class="level-filter">
                                <option value="">🔍 All Levels</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">🏋️ {{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:52px">No.</th>
                                <th>Exercise</th>
                                <th>Level</th>
                                <th>Calories</th>
                                <th>Reps</th>
                                <th>Timer</th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exerciseLevels as $index => $el)
                                <tr class="exercise-level-row" data-level="{{ $el->level_id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $el->exercise->name ?? '—' }}</td>
                                    <td>
                                        @if ($el->level)
                                            <span class="pill">{{ $el->level->name }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $el->calories }}</td>
                                    <td>{{ $el->number_of_rips ?? '—' }}</td>
                                    <td>{{ $el->timer ?? '—' }}s</td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn small gray" type="button"
                                                onclick="toggleEdit({{ $el->id }})">Edit</button>
                                            <form action="{{ route('admin.exercise-levels.delete', $el) }}"
                                                method="post" onsubmit="return confirm('Delete this exercise level?')"
                                                style="display:inline">
                                                @csrf
                                                <button class="btn small red" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="edit-row-{{ $el->id }}" style="display:none;background:#f8fafc"
                                    class="exercise-level-row" data-level="{{ $el->level_id }}">
                                    <td colspan="7">
                                        <form class="form-inline"
                                            action="{{ route('admin.exercise-levels.update', $el) }}" method="post">
                                            @csrf
                                            <div class="field">
                                                <label>Exercise</label>
                                                <select name="exercise_id" required>
                                                    @foreach ($exercises as $exercise)
                                                        <option value="{{ $exercise->id }}"
                                                            {{ $el->exercise_id == $exercise->id ? 'selected' : '' }}>
                                                            {{ $exercise->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="field">
                                                <label>Level</label>
                                                <select name="level_id" required>
                                                    @foreach ($levels as $level)
                                                        <option value="{{ $level->id }}"
                                                            {{ $el->level_id == $level->id ? 'selected' : '' }}>
                                                            {{ $level->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="field">
                                                <label>Calories</label>
                                                <input type="number" name="calories" min="1"
                                                    value="{{ $el->calories }}" required />
                                            </div>
                                            <div class="field">
                                                <label>Reps</label>
                                                <input type="number" name="number_of_rips" min="1"
                                                    value="{{ $el->number_of_rips }}" />
                                            </div>
                                            <div class="field">
                                                <label>Timer (seconds)</label>
                                                <input type="number" name="timer" min="1"
                                                    value="{{ $el->timer ?? 30 }}" />
                                            </div>
                                            <div>
                                                <button class="btn" type="submit">Save</button>
                                            </div>
                                            <div>
                                                <button class="btn gray" type="button"
                                                    onclick="toggleEdit({{ $el->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="muted">No exercise levels found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/exercise-levels.js') }}"></script>
</body>

</html>
