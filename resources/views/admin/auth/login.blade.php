@php
    $faviconVer = file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'Admin Login | SettleANZ' }}</title>
    <link rel="icon" href="/favicon.ico?v={{ $faviconVer }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png?v={{ $faviconVer }}">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v={{ $faviconVer }}">
    <link rel="manifest" href="/site.webmanifest?v={{ $faviconVer }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
</head>
<body class="admin-login-body">
    <main class="admin-login-wrap">
        <section class="admin-login-card">
            <p class="eyebrow">SettleANZ admin</p>
            <h1>Welcome back</h1>
            <p class="admin-login-copy">Sign in to manage lead captures and review new enquiries from across the site.</p>

            <form class="admin-login-form" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <label>
                    <span>Email address</span>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                    @error('password')<small>{{ $message }}</small>@enderror
                </label>

                <label class="admin-login-checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>Keep me signed in</span>
                </label>

                <button class="button button--large button--full" type="submit">Sign in to admin</button>
            </form>
        </section>
    </main>
</body>
</html>


