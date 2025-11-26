@extends('layouts.legal')

@section('title', 'Datenschutzerklärung – adwallet')

@section('content')
    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Datenschutzerklärung</h1>

    <div class="prose prose-slate max-w-none">
        <p class="text-slate-600 leading-relaxed mb-8">
            Diese Datenschutzerklärung erläutert, wie adwallet personenbezogene Daten im Zusammenhang mit Wallet-Konten und Zahlungsvorgängen verarbeitet. Wir respektieren Ihre Privatsphäre und halten uns an die Bestimmungen des Schweizer Datenschutzgesetzes (DSG) und der DSGVO, soweit anwendbar.
        </p>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">1. Verantwortliche Stelle</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Verantwortlich für die Datenverarbeitung ist:
            </p>
            <div class="text-slate-700">
                <p class="font-semibold">Ilir Hoti</p>
                <p>Schächlistrasse 6</p>
                <p>8953 Dietikon, Schweiz</p>
                <p class="mt-2">
                    <strong>E-Mail:</strong> 
                    <a href="mailto:privacy@adwallet.ch" class="text-blue-600 hover:text-blue-700 underline">privacy@adwallet.ch</a>
                </p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">2. Von uns verarbeitete Daten</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.1 Identifikationsdaten</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir verarbeiten folgende Daten, die von Partner-Plattformen übermittelt werden:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Partner-Benutzer-ID (zur Zuordnung zu Ihrem Wallet-Konto)</li>
                <li>E-Mail-Adresse (für Kontoverwaltung und Kommunikation)</li>
                <li>Anzeigename (optional, falls von Partner übermittelt)</li>
                <li>Rolle oder Benutzertyp (optional, falls von Partner übermittelt)</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.2 Wallet- und Transaktionsdaten</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir speichern und verarbeiten:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Guthabensalden und Transaktionshistorie</li>
                <li>Beträge, Währungen und Zeitstempel</li>
                <li>Partner-Referenzen und Transaktions-IDs</li>
                <li>Status von Zahlungsvorgängen (erfolgreich, fehlgeschlagen, ausstehend)</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.3 Technische Daten</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir erfassen technische Informationen für Betrieb und Sicherheit:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Stripe-Zahlungs-IDs und Sitzungs-IDs</li>
                <li>IP-Adressen (für SSO-Events und Webhook-Logs)</li>
                <li>User-Agent-Informationen</li>
                <li>Zeitstempel von SSO-Anmeldungen und Webhook-Events</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">3. Zweck der Datenverarbeitung</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir verwenden Ihre Daten ausschließlich für folgende Zwecke:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Bereitstellung der Wallet-Dienstleistung (Kontoverwaltung, Guthabenverwaltung)</li>
                <li>Abwicklung von Zahlungsvorgängen über Stripe</li>
                <li>SSO-Authentifizierung von Partner-Plattformen</li>
                <li>Integration mit Partner-Marktplätzen (Webhook-Benachrichtigungen)</li>
                <li>Betrugsprävention und Sicherheit</li>
                <li>Erfüllung gesetzlicher Aufbewahrungspflichten</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">4. Rechtsgrundlage</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Die Verarbeitung Ihrer Daten erfolgt auf Grundlage von:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li><strong>Vertragserfüllung:</strong> Zur Erbringung der Wallet-Dienstleistung gemäß unseren AGB</li>
                <li><strong>Berechtigtes Interesse:</strong> Für Betrugsprävention, Sicherheit und Verbesserung unserer Dienste</li>
                <li><strong>Gesetzliche Verpflichtung:</strong> Zur Erfüllung von Aufbewahrungs- und Meldepflichten</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">5. Datenweitergabe</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">5.1 Zahlungsdienstleister</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir geben Zahlungsdaten an <strong>Stripe Payments Europe, Ltd.</strong> weiter, um Zahlungsvorgänge abzuwickeln. Stripe verarbeitet Ihre Zahlungsinformationen gemäß seiner eigenen Datenschutzerklärung.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">5.2 Partner-Plattformen</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir teilen Transaktionsdaten (Transaktions-ID, Guthabenbetrag, Status) mit Partner-Plattformen über signierte Webhooks, um Transaktionen abzuschließen und Guthaben zu synchronisieren.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">5.3 Keine Weitergabe an Dritte</h3>
            <p class="text-slate-700 leading-relaxed">
                Wir verkaufen oder vermieten Ihre Daten nicht an Dritte. Eine Weitergabe erfolgt nur, soweit gesetzlich vorgeschrieben oder zur Erfüllung unserer Dienstleistungen erforderlich.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">6. Datenspeicherung</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir speichern Ihre Daten:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li><strong>Solange Ihr Konto aktiv ist:</strong> Kontodaten und Transaktionshistorie werden aufbewahrt, solange Sie ein aktives Wallet-Konto haben</li>
                <li><strong>Gesetzliche Aufbewahrungsfristen:</strong> Transaktionsdaten werden gemäß gesetzlichen Vorschriften (in der Regel 10 Jahre) aufbewahrt</li>
                <li><strong>Logs:</strong> SSO- und Webhook-Logs werden für Sicherheits- und Audit-Zwecke für maximal 2 Jahre gespeichert</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">7. Ihre Rechte</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Sie haben folgende Rechte bezüglich Ihrer personenbezogenen Daten:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li><strong>Auskunftsrecht:</strong> Sie können Auskunft über die von uns gespeicherten Daten verlangen</li>
                <li><strong>Berichtigungsrecht:</strong> Sie können die Korrektur unrichtiger Daten verlangen</li>
                <li><strong>Löschungsrecht:</strong> Sie können die Löschung Ihrer Daten verlangen, soweit keine gesetzlichen Aufbewahrungspflichten bestehen</li>
                <li><strong>Widerspruchsrecht:</strong> Sie können der Verarbeitung Ihrer Daten widersprechen, soweit keine vertraglichen oder gesetzlichen Gründe entgegenstehen</li>
                <li><strong>Datenübertragbarkeit:</strong> Sie können die Übertragung Ihrer Daten in einem strukturierten Format verlangen</li>
            </ul>
            <p class="text-slate-700 leading-relaxed mt-4">
                Um Ihre Rechte auszuüben, kontaktieren Sie uns unter 
                <a href="mailto:privacy@adwallet.ch" class="text-blue-600 hover:text-blue-700 underline">privacy@adwallet.ch</a>.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">8. Datensicherheit</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir setzen technische und organisatorische Maßnahmen ein, um Ihre Daten zu schützen:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Verschlüsselte Datenübertragung (HTTPS/TLS)</li>
                <li>Signierte SSO-Tokens und Webhook-Payloads (HMAC-SHA256)</li>
                <li>Zugriffskontrollen und Authentifizierung</li>
                <li>Regelmäßige Sicherheitsüberprüfungen</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">9. Cookies und Tracking</h2>
            <p class="text-slate-700 leading-relaxed">
                Wir verwenden nur technisch notwendige Cookies für die Session-Verwaltung. Wir setzen keine Tracking-Cookies oder Analytics-Tools ein, die Ihre Aktivitäten über unsere Website hinaus verfolgen.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">10. Änderungen dieser Datenschutzerklärung</h2>
            <p class="text-slate-700 leading-relaxed">
                Wir behalten uns vor, diese Datenschutzerklärung anzupassen. Aktuelle Versionen werden auf dieser Seite veröffentlicht. Bei wesentlichen Änderungen informieren wir Sie per E-Mail oder über unsere Website.
            </p>
        </section>

        <section class="mt-10 pt-6 border-t border-slate-200">
            <p class="text-sm text-slate-500">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </section>
    </div>
@endsection
