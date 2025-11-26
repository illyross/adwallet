<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anmelden – adwallet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30 text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        {{-- Background gradients --}}
        <div class="pointer-events-none fixed inset-0 -z-10">
            <div class="absolute -top-40 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-br from-blue-400/20 via-indigo-400/15 to-purple-400/10 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-[600px] w-[600px] rounded-full bg-gradient-to-tr from-amber-300/20 via-orange-300/15 to-red-300/10 blur-3xl"></div>
        </div>

        {{-- Header --}}
        <header class="relative z-50 border-b border-slate-200/60 bg-white/70 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-900 to-slate-800 shadow-lg ring-1 ring-slate-900/20">
                        <span class="text-sm font-bold text-amber-400">ad</span>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-900">adwallet</span>
                </a>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="relative z-10 flex min-h-[calc(100vh-80px)] items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-8 shadow-2xl backdrop-blur-xl">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-extrabold text-slate-900">Anmelden</h1>
                        <p class="mt-2 text-sm text-slate-600">
                            Zugriff auf Ihr adwallet-Konto
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                            <ul class="text-sm text-red-800 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                E-Mail-Adresse
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="ihre@email.ch"
                            />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                Passwort
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="••••••••"
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                />
                                <span class="ml-2 text-sm text-slate-600">Angemeldet bleiben</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                    Passwort vergessen?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-[1.02]"
                        >
                            Anmelden
                        </button>
                    </form>

                    <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50/50 p-4">
                        <p class="text-xs text-blue-800 leading-relaxed">
                            <strong class="font-semibold">Hinweis:</strong> Wenn Sie über eine Partner-Plattform zu adwallet weitergeleitet wurden, werden Sie automatisch angemeldet. Falls Sie Probleme beim Anmelden haben, kontaktieren Sie bitte die Plattform, über die Sie auf adwallet zugreifen.
                        </p>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                            ← Zurück zur Startseite
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>



