<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>adwallet – Ihre universelle Werbe-Guthaben-Wallet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30 text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        {{-- Modern gradient backgrounds --}}
        <div class="pointer-events-none fixed inset-0 -z-10">
            <div class="absolute -top-40 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-br from-blue-400/20 via-indigo-400/15 to-purple-400/10 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-[600px] w-[600px] rounded-full bg-gradient-to-tr from-amber-300/20 via-orange-300/15 to-red-300/10 blur-3xl"></div>
        </div>

        {{-- Header --}}
        <header class="relative z-50 border-b border-slate-200/60 bg-white/70 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-900 to-slate-800 shadow-lg ring-1 ring-slate-900/20 group-hover:scale-105 transition-transform">
                        <span class="text-sm font-bold text-amber-400">ad</span>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-900">adwallet</span>
                </a>
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('wallet.dashboard') }}" class="rounded-lg bg-gradient-to-r from-slate-900 to-slate-800 px-5 py-2 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            Wallet öffnen
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            Anmelden
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="relative z-10">
            {{-- Hero Section --}}
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
                <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/60 bg-blue-50/80 px-4 py-2 backdrop-blur-sm">
                            <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-700">Prepaid-Wallet für Werbung & Zahlungen</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                            Ein Guthaben.<br />
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Überall akzeptiert.</span>
                        </h1>
                        <p class="text-lg leading-relaxed text-slate-600 sm:text-xl">
                            adwallet ist eine neutrale Wallet-Plattform, die Guthaben verwaltet, die Sie auf Online-Plattformen und in Geschäften ausgeben können, die adwallet als Zahlungsmethode unterstützen. Laden Sie einmal Guthaben auf und nutzen Sie Ihre digitale oder physische adwallet-Karte überall dort, wo das Logo erscheint.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105">
                                Jetzt starten
                            </a>
                            <a href="#wie-es-funktioniert" class="rounded-xl border-2 border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-700 shadow-sm hover:border-slate-400 transition-all">
                                Mehr erfahren
                            </a>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 pt-4">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>1 CHF = 1 Guthaben</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sicher & verschlüsselt</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Prepaid nur</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Preview --}}
                    <div class="relative">
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white/95 p-8 shadow-2xl backdrop-blur-xl ring-1 ring-slate-900/5 lg:p-10">
                            {{-- Decorative gradient background --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-transparent to-indigo-50/30"></div>
                            
                            <div class="relative">
                                {{-- Header --}}
                                <div class="mb-8 flex items-start justify-between">
                                    <div>
                                        <div class="mb-2 flex items-center gap-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-slate-900 to-slate-800 shadow-md">
                                                <span class="text-xs font-bold text-amber-400">ad</span>
                                            </div>
                                            <span class="text-sm font-bold text-slate-900">adwallet</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-500">Digitale Prepaid-Karte</p>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 ring-1 ring-emerald-200/50">
                                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <span class="text-xs font-semibold text-emerald-700">Aktiv</span>
                                    </div>
                                </div>

                                {{-- Card Design --}}
                                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 shadow-2xl ring-1 ring-slate-700/50">
                                    {{-- Card pattern overlay --}}
                                    <div class="absolute inset-0 opacity-10">
                                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                                    </div>
                                    
                                    <div class="relative">
                                        {{-- Card number --}}
                                        <div class="mb-8 flex items-center justify-between">
                                            <span class="text-xs font-medium tracking-wider text-slate-400">Kartennummer</span>
                                            <span class="font-mono text-sm tracking-widest text-slate-300">•••• •••• •••• 1234</span>
                                        </div>

                                        {{-- Balance --}}
                                        <div class="mb-8">
                                            <div class="mb-2 flex items-baseline gap-2">
                                                <span class="text-5xl font-extrabold tracking-tight text-white">3'200</span>
                                                <span class="text-lg font-semibold text-slate-300">Credits</span>
                                            </div>
                                            <p class="text-xs font-medium text-slate-400">Verfügbares Guthaben</p>
                                        </div>

                                        {{-- Card footer --}}
                                        <div class="flex items-center justify-between border-t border-slate-700/50 pt-6">
                                            <div>
                                                <p class="text-xs font-medium text-slate-400 mb-1">Karteninhaber</p>
                                                <p class="text-sm font-semibold text-white">MUSTER NAME</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-14 rounded-md bg-gradient-to-br from-amber-400/20 to-orange-500/20 border border-amber-400/30 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action button --}}
                                @auth
                                    <a href="{{ route('wallet.dashboard') }}" class="mt-6 block w-full rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/30 active:scale-100 text-center">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Wallet öffnen
                                        </span>
                                    </a>
                                @else
                                    <p class="mt-6 text-xs leading-relaxed text-slate-500 text-center">
                                        Guthaben können nur über Partner-Plattformen aufgeladen werden.
                                    </p>
                                @endauth

                                {{-- Info text --}}
                                <p class="mt-6 text-xs leading-relaxed text-slate-500">
                                    Nutzen Sie diese Karte überall dort, wo adwallet akzeptiert wird – online oder im Geschäft. Ihre Credits werden sicher abgebucht, ohne dass Ihre Bankkarte exponiert wird.
                                </p>
                            </div>
                        </div>
                        
                        {{-- Decorative blur --}}
                        <div class="absolute -bottom-4 -right-4 h-32 w-32 rounded-full bg-gradient-to-br from-blue-400/20 to-indigo-400/20 blur-3xl -z-10"></div>
                        <div class="absolute -top-4 -left-4 h-24 w-24 rounded-full bg-gradient-to-br from-amber-300/15 to-orange-300/15 blur-2xl -z-10"></div>
                    </div>
                </div>
            </section>

            {{-- Features Grid --}}
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="group rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-lg backdrop-blur-sm transition-all hover:shadow-xl hover:scale-105">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Sicher & verschlüsselt</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Alle Aufladungen werden sicher verarbeitet. 1 CHF entspricht immer 1 Guthaben für klare, nachvollziehbare Salden.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-lg backdrop-blur-sm transition-all hover:shadow-xl hover:scale-105">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Neutral & konform</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Inhalte und Metadaten bleiben neutral; jede Plattform oder jedes Geschäft behält die volle Kontrolle über eigene Produkte, Angebote und Richtlinien.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-lg backdrop-blur-sm transition-all hover:shadow-xl hover:scale-105">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Ein Guthaben, viele Anwendungen</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Dieselben Guthaben können für digitale Kampagnen, On-Site-Promotion oder Geschäftsdienstleistungen verwendet werden, wo immer adwallet akzeptiert wird.
                        </p>
                    </div>
                </div>
            </section>

            {{-- How it works --}}
            <section id="wie-es-funktioniert" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">So funktioniert adwallet</h2>
                    <p class="mt-4 text-lg text-slate-600">Drei einfache Schritte zu Ihrem universellen Werbe-Guthaben</p>
                </div>
                <div class="grid gap-8 md:grid-cols-3">
                    <div class="relative rounded-2xl border border-slate-200 bg-white/80 p-8 shadow-lg backdrop-blur-sm">
                        <div class="absolute -top-4 -left-4 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-lg font-bold text-white shadow-lg">
                            1
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-slate-900">Guthaben aufladen</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Laden Sie Guthaben über eine Partner-Plattform auf, die adwallet integriert hat. Wählen Sie einen Betrag in CHF und schließen Sie eine sichere Aufladung ab. 1 CHF wird in 1 Guthaben umgewandelt.
                        </p>
                    </div>
                    <div class="relative rounded-2xl border border-slate-200 bg-white/80 p-8 shadow-lg backdrop-blur-sm">
                        <div class="absolute -top-4 -left-4 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-600 to-teal-600 text-lg font-bold text-white shadow-lg">
                            2
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-slate-900">Guthaben dort nutzen, wo adwallet erscheint</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Wenn eine Online-Plattform oder ein Geschäft adwallet als Zahlungsoption anbietet, wählen Sie es an der Kasse. Der Partner fordert einen Betrag an; adwallet validiert und belastet Ihre Guthaben.
                        </p>
                    </div>
                    <div class="relative rounded-2xl border border-slate-200 bg-white/80 p-8 shadow-lg backdrop-blur-sm">
                        <div class="absolute -top-4 -left-4 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-600 to-orange-600 text-lg font-bold text-white shadow-lg">
                            3
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-slate-900">Partner erhalten Abrechnung</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            adwallet benachrichtigt die Plattform oder den Händler in Echtzeit und gleicht Salden im Hintergrund ab. Sie sehen jede Belastung als Transaktion in Ihrer Wallet-Historie.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Use cases --}}
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Was Sie mit adwallet-Guthaben tun können</h2>
                    <p class="mt-4 text-lg text-slate-600">Ein Guthaben für alle Ihre Werbe- und Zahlungsbedürfnisse</p>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-blue-50/50 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Digitale Kampagnen finanzieren</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Nutzen Sie Guthaben, um gesponserte Platzierungen zu kaufen, Angebote hervorzuheben oder die Reichweite von Kampagnen auf Plattformen zu steigern, die adwallet als Zahlungsmethode integriert haben.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-emerald-50/50 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Mit Ihrer Karte in Geschäften bezahlen</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Präsentieren Sie Ihre adwallet-Karte oder Kontonummer an teilnehmenden Standorten. Guthaben werden von Ihrem Prepaid-Guthaben abgebucht, anstatt eine traditionelle Karte zu belasten.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-amber-50/50 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Budgets zentralisieren</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Behalten Sie einen zentralen Guthabenpool für mehrere Marken, Teams oder Standorte. Verfolgen Sie die Nutzung pro Plattform oder Geschäft, während Sie Aufladungen an einem Ort verwalten.
                        </p>
                    </div>
                </div>
            </section>

            {{-- For platforms, merchants, individuals --}}
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Für Plattformen, Händler und Privatpersonen</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border-2 border-blue-200 bg-white/90 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Online-Plattformen</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Fügen Sie adwallet neben Ihren bestehenden Zahlungsmethoden hinzu, um Werbe-Guthaben von Kerngeschäftseinnahmen zu trennen. Sie behalten die Kontrolle über Preise, Kampagnen und Regeln; adwallet verwaltet nur Zahlungen und Webhooks.
                        </p>
                    </div>
                    <div class="rounded-2xl border-2 border-emerald-200 bg-white/90 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Geschäfte</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Akzeptieren Sie Prepaid-adwallet-Guthaben an der Kasse oder über Geschäftssysteme. Kunden, die bereits Guthaben haben, können schnell bezahlen, ohne ihre zugrunde liegenden Karten preiszugeben.
                        </p>
                    </div>
                    <div class="rounded-2xl border-2 border-amber-200 bg-white/90 p-6 shadow-lg">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Privatpersonen & Unternehmen</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Nutzen Sie eine einzige Wallet, um Werbung und damit verbundene Ausgaben über mehrere Eigenschaften und Geschäfte hinweg zu verwalten. Laden Sie Guthaben vor, weisen Sie Budgets zu und reduzieren Sie die Anzahl der Kartenbelastungen auf Ihren Kontoauszügen.
                        </p>
                    </div>
                </div>
            </section>

            {{-- CTA Section --}}
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-12 text-center shadow-2xl">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Bereit, loszulegen?</h2>
                    <p class="mt-4 text-lg text-slate-300">Starten Sie noch heute mit adwallet und vereinfachen Sie Ihre Werbeausgaben.</p>
                    <div class="mt-8 flex justify-center gap-4">
                        @auth
                            <a href="{{ route('wallet.dashboard') }}" class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105">
                                Wallet öffnen
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-3 text-base font-semibold text-white shadow-lg hover:shadow-xl transition-all hover:scale-105">
                                Jetzt anmelden
                            </a>
                        @endauth
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="relative z-10 border-t border-slate-200 bg-white/90 backdrop-blur-xl mt-20">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-sm text-slate-600">© {{ now()->year }} adwallet. Alle Rechte vorbehalten.</p>
                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        <a href="{{ route('legal.imprint') }}" class="text-slate-600 hover:text-slate-900 transition-colors">Impressum</a>
                        <a href="{{ route('legal.privacy') }}" class="text-slate-600 hover:text-slate-900 transition-colors">Datenschutz</a>
                        <a href="{{ route('legal.terms') }}" class="text-slate-600 hover:text-slate-900 transition-colors">AGB</a>
                        <a href="{{ route('legal.acceptable-use') }}" class="text-slate-600 hover:text-slate-900 transition-colors">Nutzungsbedingungen</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
