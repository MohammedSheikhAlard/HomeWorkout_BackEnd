<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
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
            <a class="active" href="{{ route('admin.users') }}">Users</a>
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
            <div class="page-title"><h1>Users</h1></div>
        </div>

        <div class="page">
            @if(session('success'))
                <div class="alert success" style="background: #dcfce7; border: 1px solid #22c55e; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert error" style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card">
                <div class="toolbar">
                    <div class="muted">All Users (<span id="usersCount">{{ $users->count() }}</span> total)</div>
                    <div>
                        <select id="levelFilter" onchange="filterUsers()" class="level-filter">
                            <option value="">🔍 All Levels</option>
                            <option value="no-level">❌ No Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">🏋️ {{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table users-table">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th style="width:120px">Name</th>
                            <th style="width:180px">Email</th>
                            <th style="width:80px">Gender</th>
                            <th style="width:100px">Level</th>
                            <th style="width:140px">Wallet</th>
                            <th style="width:90px">Status</th>
                            <th style="width:100px">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr class="user-row" data-level="{{ $user->level_id ?? 'no-level' }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="user-name">{{ $user->name }}</td>
                            <td class="user-email">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->gender ?? '—' }}</td>
                            <td class="text-center">
                                @if($user->level)
                                    <span class="pill small">{{ $user->level->name }}</span>
                                @else
                                    <span class="pill small no-level">No Level</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->wallet)
                                    <div class="wallet-info">
                                        <span class="pill small wallet-balance">${{ number_format($user->wallet->balance, 2) }}</span>
                                        <button class="btn tiny orange" onclick="editWallet({{ $user->id }}, {{ $user->wallet->balance }})">Edit</button>
                                    </div>
                                @else
                                    <div class="wallet-info">
                                        <span class="pill small no-level">No Wallet</span>
                                        <button class="btn tiny green" onclick="createWallet({{ $user->id }})">Create</button>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="pill small verified">✓</span>
                                @else
                                    <span class="pill small not-verified">✗</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $user->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="muted">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="walletModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Wallet Balance</h3>
            <span class="close" onclick="closeWalletModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="walletForm" method="post">
                @csrf
                <div class="field">
                    <label>Current Balance</label>
                    <input type="text" id="currentBalance" readonly style="background: #f1f5f9;" />
                </div>
                <div class="field">
                    <label>Action</label>
                    <select id="walletAction" name="walletAction" required>
                        <option value="">Choose action</option>
                        <option value="add">Add Money (+)</option>
                        <option value="subtract">Subtract Money (-)</option>
                        <option value="set">Set New Balance</option>
                    </select>
                </div>
                <div class="field">
                    <label>Amount</label>
                    <input type="number" id="walletAmount" name="walletAmount" step="0.01" min="0" required />
                </div>
                <div class="field">
                    <label>New Balance</label>
                    <input type="text" id="newBalance" readonly style="background: #f1f5f9;" />
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn">Save Changes</button>
                    <button type="button" class="btn gray" onclick="closeWalletModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/tables.js') }}"></script>
<script src="{{ asset('js/wallet.js') }}"></script>
</body>
</html>
