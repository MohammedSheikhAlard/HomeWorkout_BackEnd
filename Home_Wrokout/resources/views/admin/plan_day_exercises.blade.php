<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Day Exercises - Day {{ $planDay->day_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plan-day-exercises.css') }}">
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
            <div class="page-title"><h1>Exercises for Day {{ $planDay->day_number }}</h1></div>
            <div class="user"><div class="avatar">AD</div></div>
        </div>

        <div class="page">
            @if(!$planDay->is_rest_day)
            <div class="add-button-container">
                <button class="btn green" type="button" onclick="toggleAddForm()">
                    <span id="addButtonText">+ Add New Exercise</span>
                </button>
            </div>

            <div class="card add-form-container" id="addFormContainer" style="margin-bottom:14px">
                <div class="toolbar" style="margin-bottom:8px"><div class="muted">Add New Exercise</div></div>
                <form class="form-inline" action="{{ route('admin.plans.day.exercises.store', $planDay) }}" method="post">
                    @csrf
                    <div class="field">
                        <label>Exercise</label>
                        <select name="exercies_level_id" required>
                            <option value="" disabled selected>Choose exercise</option>
                            @foreach($availableExerciseLevels as $el)
                                <option value="{{ $el->id }}">{{ $el->name }} ({{ $el->calories }} cal)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Order</label>
                        <input type="number" name="exercies_order" min="1" required />
                    </div>
                    <div>
                        <button class="btn" type="submit">Add Exercise</button>
                    </div>
                    <div>
                        <button class="btn gray" type="button" onclick="toggleAddForm()">Cancel</button>
                    </div>
                </form>
            </div>
            @endif
            <div class="card">
                <div class="toolbar">
                    <div class="muted">Plan: {{ optional($planDay->plan)->name ?? '#' }}</div>
                    <div>
                        <a class="btn gray" href="{{ route('admin.plans.days', $planDay->plan) }}">Back to Days</a>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:52px">No.</th>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Calories</th>
                            <th>Reps</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exercises as $index => $ex)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ex->exercies_order }}</td>
                            <td>{{ $ex->name }}</td>
                            <td>{{ $ex->description }}</td>
                            <td>{{ $ex->calories }}</td>
                            <td>{{ $ex->number_of_rips ?? '—' }}</td>
                            <td>
                                @if($ex->image_path)
                                    <img class="img" src="{{ asset('imgs/' . $ex->image_path) }}" alt="{{ $ex->name }}" />
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn small gray" type="button" onclick="toggleEdit({{ $ex->id }})">Edit</button>
                                    <form action="{{ route('admin.plans.day.exercises.delete', $ex->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this exercise?')" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn small red">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="edit-row-{{ $ex->id }}" style="display:none;background:#f8fafc">
                            <td colspan="8">
                                <form class="form-inline" action="{{ route('admin.plans.day.exercises.update', $ex->id) }}" method="post">
                                    @csrf
                                    <div class="field">
                                        <label>Exercise</label>
                                        <select name="exercies_level_id" required>
                                            @foreach($availableExerciseLevels as $el)
                                                <option value="{{ $el->id }}" {{ $ex->exercies_level_id == $el->id ? 'selected' : '' }}>{{ $el->name }} ({{ $el->calories }} cal)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Order</label>
                                        <input type="number" name="exercies_order" min="1" value="{{ $ex->exercies_order }}" required />
                                    </div>
                                    <div>
                                        <button class="btn" type="submit">Save</button>
                                    </div>
                                    <div>
                                        <button class="btn gray" type="button" onclick="toggleEdit({{ $ex->id }})">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="muted">No exercises assigned to this day.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/plan-day-exercises.js') }}"></script>
</body>
</html>



