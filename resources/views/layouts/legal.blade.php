<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'adwallet')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30 text-slate-900 antialiased">
    <div class="min-h-screen flex items-start justify-center px-4 py-10 sm:py-16">
        <div class="w-full max-w-4xl">
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-900 to-slate-800 shadow-lg ring-1 ring-slate-900/20 group-hover:scale-105 transition-transform">
                        <span class="text-sm font-bold text-amber-400">ad</span>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-900">adwallet</span>
                </a>
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                    ← Zurück
                </a>
            </div>

            {{-- Content Card --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-2xl backdrop-blur-xl px-6 py-8 sm:px-10 sm:py-12">
                @yield('content')
            </div>

            {{-- Footer Links --}}
            <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-slate-600">
                <a href="{{ route('legal.imprint') }}" class="hover:text-slate-900 transition-colors">Impressum</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('legal.privacy') }}" class="hover:text-slate-900 transition-colors">Datenschutz</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('legal.terms') }}" class="hover:text-slate-900 transition-colors">AGB</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('legal.acceptable-use') }}" class="hover:text-slate-900 transition-colors">Nutzungsbedingungen</a>
            </div>
        </div>
    </div>
</body>
</html>
