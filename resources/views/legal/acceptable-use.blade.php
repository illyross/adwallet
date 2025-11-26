@extends('layouts.legal')

@section('title', 'Nutzungsbedingungen – adwallet')

@section('content')
    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Nutzungsbedingungen (Acceptable Use Policy)</h1>

    <div class="prose prose-slate max-w-none">
        <p class="text-slate-600 leading-relaxed mb-8">
            Diese Nutzungsbedingungen legen fest, welche Verwendungen des adwallet-Dienstes erlaubt und welche untersagt sind. adwallet stellt ausschließlich Zahlungs- und Wallet-Infrastruktur bereit. Alle Werbeinhalte, Angebote und Listings unterliegen den Richtlinien der jeweiligen Partner-Marktplätze, die den geltenden Gesetzen und den Richtlinien der Zahlungsdienstleister entsprechen müssen.
        </p>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">1. Erlaubte Nutzung</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                adwallet darf für folgende Zwecke verwendet werden:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Kauf von Werbe-Guthaben für die Verwendung auf Partner-Plattformen</li>
                <li>Zahlung für Werbekampagnen, gesponserte Platzierungen oder Premium-Listings</li>
                <li>Zahlung in physischen Geschäften, die adwallet als Zahlungsmethode akzeptieren</li>
                <li>Verwaltung von Werbe-Budgets über mehrere Plattformen hinweg</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">2. Verbotene Nutzung der Wallet</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Die folgenden Nutzungen sind ausdrücklich untersagt:
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.1 Umgehung von Zahlungsbeschränkungen</h3>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Verwendung von adwallet zur Umgehung von Beschränkungen von Kreditkartennetzwerken, Banken oder Zahlungsdienstleistern</li>
                <li>Nutzung zur Umgehung von geografischen oder rechtlichen Beschränkungen</li>
                <li>Verwendung für Transaktionen, die gegen die AGB von Stripe oder anderen Zahlungsdienstleistern verstoßen</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.2 Geldtransfer und Remittances</h3>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Verwendung des Dienstes für Geldtransfers, Überweisungen oder Remittances</li>
                <li>Nutzung als allgemeine Wertaufbewahrung (Stored Value) außerhalb des Werbe-Kontexts</li>
                <li>Verwendung für Zahlungen, die nicht mit Werbe-Guthaben zusammenhängen</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.3 Technische Manipulation</h3>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Reverse Engineering, Dekompilierung oder Manipulation der SSO- oder Webhook-Integrationen</li>
                <li>Versuch, Sicherheitsmaßnahmen zu umgehen oder zu umgehen</li>
                <li>Missbrauch von API-Endpunkten durch automatisierte Skripte oder Bots (außerhalb erlaubter Nutzung)</li>
                <li>Durchführung von DDoS-Angriffen oder anderen Angriffen auf die Infrastruktur</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.4 Betrug und Geldwäsche</h3>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Verwendung für betrügerische oder betrügerische Zwecke</li>
                <li>Geldwäsche oder Terrorismusfinanzierung</li>
                <li>Verwendung gestohlener Zahlungsmethoden oder Identitäten</li>
                <li>Erstellung mehrerer Konten zur Umgehung von Limits oder Beschränkungen</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.5 Illegale Aktivitäten</h3>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Verwendung für illegale Waren oder Dienstleistungen</li>
                <li>Unterstützung von Aktivitäten, die gegen schweizerisches oder internationales Recht verstoßen</li>
                <li>Verwendung für Glücksspiel oder andere regulierte Aktivitäten ohne entsprechende Lizenzen</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">3. Verantwortlichkeiten der Partner</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Partner-Plattformen und Geschäfte, die adwallet integrieren, sind verantwortlich für:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li><strong>Inhalts-Compliance:</strong> Sicherstellung, dass alle Werbeinhalte, Kampagnen, Listings und Zielgruppenausrichtung den lokalen Vorschriften entsprechen</li>
                <li><strong>Zahlungs-Compliance:</strong> Einhaltung der AGB und Richtlinien von Stripe und anderen Zahlungsdienstleistern</li>
                <li><strong>Altersverifikation:</strong> Implementierung angemessener Altersverifikations- und Jugendschutzmaßnahmen, falls erforderlich</li>
                <li><strong>Datenverarbeitung:</strong> Einhaltung der Datenschutzbestimmungen bei der Verarbeitung von Nutzerdaten</li>
                <li><strong>Betrugsprävention:</strong> Implementierung von Maßnahmen zur Erkennung und Verhinderung von Betrug</li>
            </ul>
            <p class="text-slate-700 leading-relaxed">
                adwallet übernimmt keine Verantwortung für Inhalte, Angebote oder Compliance-Verstöße von Partnern. Jeder Partner ist für seine eigenen Inhalte und Geschäftspraktiken verantwortlich.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">4. Überwachung und Durchsetzung</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.1 Überwachung</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir überwachen die Nutzung des Dienstes auf verdächtige Aktivitäten, Betrug oder Verstöße gegen diese Nutzungsbedingungen. Dies umfasst:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Analyse von Transaktionsmustern</li>
                <li>Überprüfung von SSO-Events und Webhook-Logs</li>
                <li>Zusammenarbeit mit Zahlungsdienstleistern bei Betrugserkennung</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.2 Durchsetzungsmaßnahmen</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Bei Verstößen gegen diese Nutzungsbedingungen behalten wir uns vor:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Warnung des Nutzers oder Partners</li>
                <li>Vorübergehende oder dauerhafte Sperrung von Konten</li>
                <li>Kündigung von Partner-Verträgen</li>
                <li>Meldung an Strafverfolgungsbehörden bei schwerwiegenden Verstößen</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.3 Keine Haftung für Partner-Verstöße</h3>
            <p class="text-slate-700 leading-relaxed">
                adwallet haftet nicht für Verstöße von Partnern gegen Gesetze, Vorschriften oder ihre eigenen Inhaltsrichtlinien. Partner sind allein verantwortlich für die Einhaltung aller geltenden Bestimmungen.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">5. Meldung von Verstößen</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wenn Sie Verstöße gegen diese Nutzungsbedingungen bemerken, melden Sie diese bitte unter:
            </p>
            <p class="text-slate-700">
                <strong>E-Mail:</strong> 
                <a href="mailto:abuse@adwallet.ch" class="text-blue-600 hover:text-blue-700 underline">abuse@adwallet.ch</a>
            </p>
            <p class="text-slate-700 mt-2">
                Bitte geben Sie so viele Details wie möglich an, einschließlich Transaktions-IDs, Partner-Namen und Beschreibung des Verstoßes.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">6. Änderungen dieser Nutzungsbedingungen</h2>
            <p class="text-slate-700 leading-relaxed">
                Wir behalten uns vor, diese Nutzungsbedingungen anzupassen, um auf neue Bedrohungen, rechtliche Anforderungen oder Änderungen im Dienst zu reagieren. Wesentliche Änderungen werden auf dieser Seite veröffentlicht und können per E-Mail kommuniziert werden.
            </p>
        </section>

        <section class="mt-10 pt-6 border-t border-slate-200">
            <p class="text-sm text-slate-500">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </section>
    </div>
@endsection
