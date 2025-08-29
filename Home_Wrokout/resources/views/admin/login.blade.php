<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="card">
        <div class="avatar" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.761 0 5-2.462 5-5.5S14.761 1 12 1 7 3.462 7 6.5 9.239 12 12 12zm0 2c-4.337 0-8 2.686-8 6v1h16v-1c0-3.314-3.663-6-8-6z"/>
            </svg>
        </div>
        <h1>تسجيل دخول الأدمن</h1>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form id="login-form" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <input type="text" name="username" placeholder="اسم المستخدم" required autofocus>
                <span class="icon" aria-hidden="true">👤</span>
            </div>
            <div class="field">
                <input type="password" name="password" placeholder="كلمة المرور" required>
                <span class="icon" aria-hidden="true">🔒</span>
            </div>
            <button type="submit" class="btn">تسجيل الدخول</button>
        </form>
        <div class="hint">بيانات افتراضية للتجربة: <span class="kbd">admin</span> / <span class="kbd">123456</span></div>
    </div>

    <script src="{{ asset('js/admin-login.js') }}"></script>
</body>
</html>


