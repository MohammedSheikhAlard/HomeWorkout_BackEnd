<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
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
            <a class="active" href="{{ route('admin.plans') }}">Plans</a>
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
            <div class="page-title"><h1>Plans</h1></div>
        </div>

        <div class="page">
            <!-- Add New Plan Button -->
            <div class="add-button-container">
                <button class="btn green" type="button" onclick="toggleAddForm(this)">
                    <span id="addButtonText">+ Add New Plan</span>
                </button>
                <a href="{{ route('admin.plans.trashed') }}" class="btn gray" style="text-decoration: none; display: inline-block;">
                    🗑️ View Deleted Plans
                </a>
            </div>

            <!-- Add New Plan Form (Hidden by default) -->
            <div class="card add-form-container" id="addFormContainer" style="margin-bottom:14px">
                <div class="toolbar" style="margin-bottom:8px"><div class="muted">Add New Plan</div></div>
                <form class="form-inline" action="{{ route('admin.plans.store') }}" method="post">
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
                        <label>Price (optional)</label>
                        <input type="number" name="price" min="0" step="0.01" placeholder="Leave empty for free plan" />
                    </div>
                    <div class="field">
                        <label>Days to Train</label>
                        <input type="number" name="number_of_day_to_train" min="1" required />
                    </div>
                    <div class="field">
                        <label>Level</label>
                        <select name="level_id" required>
                            <option value="" disabled selected>Choose level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
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
                    <div class="muted">All Plans grouped by Level (<span id="plansCount">{{ $plans->count() }}</span> total)</div>
                    <div>
                        <select id="levelFilter" onchange="filterPlans()" style="border:1px solid var(--border);border-radius:8px;padding:8px;margin-right:10px;min-width:150px">
                            <option value="">🔍 All Levels</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">🏋️ {{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:52px">No.</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Days</th>
                            <th>Level</th>
                            <th style="width:120px">Days</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $index => $plan)
                        <tr class="plan-row" data-level="{{ $plan->level_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->description }}</td>
                            <td>
                                @if($plan->price)
                                    ${{ number_format($plan->price, 2) }}
                                @else
                                    <span class="pill" style="background:#dcfce7;color:#166534">Free</span>
                                @endif
                            </td>
                            <td>{{ $plan->number_of_day_to_train }}</td>
                            <td>
                                @if($plan->level)
                                    <span class="pill">{{ $plan->level->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a class="btn small" href="{{ route('admin.plans.days', $plan) }}">View Days</a>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn small gray" type="button" onclick="toggleEdit({{ $plan->id }})">Edit</button>
                                    <form action="{{ route('admin.plans.delete', $plan) }}" method="post" onsubmit="return confirm('Delete this plan? It will be soft-deleted.')">
                                        @csrf
                                        <button class="btn small red" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="edit-row-{{ $plan->id }}" style="display:none;background:#f8fafc" class="plan-row" data-level="{{ $plan->level_id }}">
                            <td colspan="8">
                                <form class="form-inline" action="{{ route('admin.plans.update', $plan) }}" method="post">
                                    @csrf
                                    <div class="field">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{ $plan->name }}" required />
                                    </div>
                                    <div class="field" style="flex:1;min-width:320px">
                                        <label>Description</label>
                                        <input type="text" name="description" value="{{ $plan->description }}" required />
                                    </div>
                                    <div class="field">
                                        <label>Price (optional)</label>
                                        <input type="number" name="price" min="0" step="0.01" value="{{ $plan->price }}" placeholder="Leave empty for free plan" />
                                    </div>
                                    <div class="field">
                                        <label>Days to Train</label>
                                        <input type="number" name="number_of_day_to_train" min="1" value="{{ $plan->number_of_day_to_train }}" required />
                                    </div>
                                    <div class="field">
                                        <label>Level</label>
                                        <select name="level_id" required>
                                            @foreach($levels as $level)
                                                <option value="{{ $level->id }}" {{ $plan->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <button class="btn" type="submit">Save</button>
                                    </div>
                                    <div>
                                        <button class="btn gray" type="button" onclick="toggleEdit({{ $plan->id }})">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="muted">No plans found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

    <script src="{{ asset('js/tables.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.preventScrollOnSubmit();
        });
    </script>
</body>
</html>



