<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Days - {{ $plan->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plan-days.css') }}">
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
                    <h1>Plan Days - {{ $plan->name }}</h1>
                </div>
                <div class="user">
                    <div class="avatar">AD</div>
                </div>
            </div>

            <div class="page">
                <div class="add-button-container">
                    <button class="btn green" type="button" onclick="toggleAddForm()">
                        <span id="addButtonText">+ Add New Day</span>
                    </button>
                </div>

                <div class="card add-form-container" id="addFormContainer" style="margin-bottom:14px">
                    <div class="toolbar" style="margin-bottom:8px">
                        <div class="muted">Add New Day</div>
                    </div>
                    <form class="form-inline" action="{{ route('admin.plans.days.store', $plan) }}" method="post">
                        @csrf
                        <div class="field">
                            <label>Day Number</label>
                            <input type="number" name="day_number" min="1" required />
                        </div>
                        <div class="field">
                            <label>Total Calories</label>
                            <input type="number" name="total_calories" min="0" value="0" readonly
                                class="readonly-input" title="يتم حساب السعرات الحرارية تلقائياً من التمارين المضافة" />
                        </div>
                        <div class="field">
                            <label>Rest Day</label>
                            <select name="is_rest_day" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
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
                        <div class="muted">Total Days: {{ $days->count() }}</div>
                        <div>
                            <a class="btn gray" href="{{ route('admin.plans') }}">Back to Plans</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:52px">No.</th>
                                <th>Day Number</th>
                                <th>Total Calories <span class="total-calories-auto"></span></th>
                                <th>Rest Day</th>
                                <th style="width:140px">Exercises</th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($days as $index => $day)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $day->day_number }}</td>
                                    <td>{{ $day->total_calories ?? '—' }}</td>
                                    <td>
                                        <form action="{{ route('admin.plans.days.toggle-rest', $day) }}"
                                            method="post">
                                            @csrf
                                            <button class="btn small {{ $day->is_rest_day ? 'red' : '' }}"
                                                type="submit">
                                                {{ $day->is_rest_day ? 'Disable Rest' : 'Enable Rest' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        @if (!$day->is_rest_day)
                                            <a class="btn small"
                                                href="{{ route('admin.plans.day.exercises', $day) }}">View
                                                Exercises</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn small gray" type="button"
                                                onclick="toggleEdit({{ $day->id }})">Edit</button>
                                            <form action="{{ route('admin.plans.days.delete', $day) }}" method="post"
                                                onsubmit="return confirm('Delete this day?')" style="display:inline">
                                                @csrf
                                                <button class="btn small red" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="edit-row-{{ $day->id }}" style="display:none;background:#f8fafc">
                                    <td colspan="6">
                                        <form class="form-inline" action="{{ route('admin.plans.days.update', $day) }}"
                                            method="post">
                                            @csrf
                                            <div class="field">
                                                <label>Day Number</label>
                                                <input type="number" name="day_number" min="1"
                                                    value="{{ $day->day_number }}" required />
                                            </div>
                                            <div class="field">
                                                <label>Total Calories</label>
                                                <input type="number" name="total_calories" min="0"
                                                    value="{{ $day->total_calories }}" readonly
                                                    class="readonly-input"
                                                    title="يتم حساب السعرات الحرارية تلقائياً من التمارين المضافة" />
                                            </div>
                                            <div class="field">
                                                <label>Rest Day</label>
                                                <select name="is_rest_day" required>
                                                    <option value="0"
                                                        {{ $day->is_rest_day == 0 ? 'selected' : '' }}>No</option>
                                                    <option value="1"
                                                        {{ $day->is_rest_day == 1 ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                            <div>
                                                <button class="btn" type="submit">Save</button>
                                            </div>
                                            <div>
                                                <button class="btn gray" type="button"
                                                    onclick="toggleEdit({{ $day->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="muted">No days for this plan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/plan-days.js') }}"></script>
</body>

</html>
