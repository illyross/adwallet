@extends('layouts.legal')

@section('title', 'Impressum – adwallet')

@section('content')
    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Impressum</h1>

    <div class="prose prose-slate max-w-none">
        <p class="text-slate-600 leading-relaxed mb-8">
            Diese Seite enthält die gesetzlich vorgeschriebenen Angaben gemäß Schweizer Recht für die digitale Wallet-Plattform adwallet.
        </p>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Rechtsträger</h2>
            <div class="space-y-2 text-slate-700">
                <p class="font-semibold">Ilir Hoti</p>
                <p>Schächlistrasse 6</p>
                <p>8953 Dietikon</p>
                <p>Schweiz</p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Kontakt</h2>
            <div class="space-y-2 text-slate-700">
                <p>
                    <strong>E-Mail:</strong> 
                    <a href="mailto:support@adwallet.ch" class="text-blue-600 hover:text-blue-700 underline">support@adwallet.ch</a>
                </p>
                <p>
                    <strong>Website:</strong> 
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 underline">adwallet.ch</a>
                </p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Geschäftstätigkeit</h2>
            <p class="text-slate-700 leading-relaxed">
                adwallet betreibt eine neutrale digitale Wallet-Plattform für Werbe-Guthaben, die von Partner-Marktplätzen und Geschäften als Zahlungsmethode akzeptiert wird. Die Plattform stellt ausschließlich Zahlungsinfrastruktur bereit und hostet keine Produktangebote, Werbeinhalte oder Nutzer-generierte Inhalte.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Zahlungsabwicklung</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Alle Zahlungsvorgänge werden über Stripe abgewickelt:
            </p>
            <div class="text-slate-700">
                <p><strong>Stripe Payments Europe, Ltd.</strong></p>
                <p>1 Grand Canal Street Lower</p>
                <p>Dublin 2, Irland</p>
                <p class="mt-2">
                    <a href="https://stripe.com" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 underline">stripe.com</a>
                </p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Haftungsausschluss</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                adwallet übernimmt keine Haftung für:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Inhalte, Angebote oder Dienstleistungen, die von Partner-Plattformen bereitgestellt werden</li>
                <li>Verfügbarkeit oder Funktionalität externer Zahlungsdienstleister</li>
                <li>Verluste aufgrund von technischen Störungen, die außerhalb des Einflussbereichs von adwallet liegen</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Urheberrecht</h2>
            <p class="text-slate-700 leading-relaxed">
                Alle Inhalte dieser Website, einschließlich Texte, Grafiken, Logos und Software, sind urheberrechtlich geschützt. Die Vervielfältigung, Verbreitung oder Nutzung ohne ausdrückliche schriftliche Genehmigung ist untersagt.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Aufsichtsbehörden</h2>
            <p class="text-slate-700 leading-relaxed">
                Für Fragen zur Regulierung von Zahlungsdienstleistungen in der Schweiz ist die 
                <a href="https://www.finma.ch" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 underline">FINMA (Eidgenössische Finanzmarktaufsicht)</a> 
                zuständig.
            </p>
        </section>

        <section class="mt-10 pt-6 border-t border-slate-200">
            <p class="text-sm text-slate-500">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </section>
    </div>
@endsection
