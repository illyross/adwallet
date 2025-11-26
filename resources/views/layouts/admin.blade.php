<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'adwallet Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm border-b border-slate-200">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold text-slate-900">@yield('heading', 'Admin Dashboard')</h1>
                    <nav class="flex items-center gap-4">
                        <a href="{{ route('admin.wallet.sso') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">SSO Logs</a>
                        <a href="{{ route('admin.wallet.webhooks') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Webhook Logs</a>
                        <a href="{{ route('admin.password.change') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Passwort ändern</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </nav>
                </div>
            </div>
        </header>
        <main class="flex-1 mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>


