@extends('layouts.admin')

@section('title', 'Passwort ändern – adwallet Admin')
@section('heading', 'Passwort ändern')

@section('content')
    <div class="max-w-2xl bg-white rounded-lg shadow-lg p-6 sm:p-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Passwort ändern</h2>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                <p class="text-sm text-emerald-800">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="text-sm text-red-800 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Aktuelles Passwort
                </label>
                <input
                    id="current_password"
                    type="password"
                    name="current_password"
                    required
                    autofocus
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="Aktuelles Passwort eingeben"
                />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Neues Passwort
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="Neues Passwort eingeben"
                />
                <p class="mt-2 text-xs text-slate-500">
                    Mindestens 8 Zeichen empfohlen
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                    Neues Passwort bestätigen
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="Neues Passwort wiederholen"
                />
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    class="rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105"
                >
                    Passwort ändern
                </button>
                <a
                    href="{{ route('admin.wallet.sso') }}"
                    class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all"
                >
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
@endsection

