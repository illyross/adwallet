@extends('layouts.legal')

@section('title', 'Allgemeine Geschäftsbedingungen – adwallet')

@section('content')
    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Allgemeine Geschäftsbedingungen (AGB)</h1>

    <div class="prose prose-slate max-w-none">
        <p class="text-slate-600 leading-relaxed mb-8">
            Diese Allgemeinen Geschäftsbedingungen regeln die Nutzung des adwallet-Dienstes als neutrale Wallet-Plattform für den Kauf und die Verwaltung von Werbe-Guthaben auf Partner-Marktplätzen und in teilnehmenden Geschäften.
        </p>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">1. Geltungsbereich und Vertragsgegenstand</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Diese AGB gelten für alle Nutzer des adwallet-Dienstes. Durch die Nutzung des Dienstes akzeptieren Sie diese Bedingungen. adwallet stellt eine neutrale Zahlungsinfrastruktur bereit und hostet keine Produktangebote, Werbeinhalte oder Nutzer-generierte Inhalte.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">2. Dienstleistungsumfang</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.1 Wallet-Funktionalität</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                adwallet bietet folgende Dienstleistungen:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Verwaltung von Prepaid-Guthaben für Werbeausgaben</li>
                <li>Abwicklung von Zahlungsvorgängen über Stripe</li>
                <li>SSO-Authentifizierung von Partner-Plattformen</li>
                <li>Webhook-Benachrichtigungen für Transaktionen</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">2.2 Keine Inhaltshosting</h3>
            <p class="text-slate-700 leading-relaxed">
                adwallet hostet keine Angebote, Werbeinhalte, Kampagnen oder Nutzer-generierte Inhalte. Alle Inhalte, Werbekampagnen und Compliance-Verantwortlichkeiten bleiben bei den jeweiligen Partner-Plattformen und Geschäften.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">3. Benutzerkonten und Guthaben</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">3.1 Kontoerstellung</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Konten werden automatisch erstellt, wenn Sie von einer Partner-Plattform zu adwallet weitergeleitet werden. Sie können sich auch direkt über die Login-Seite anmelden, falls Sie bereits ein Konto haben.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">3.2 Guthaben und Währung</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Guthaben werden wie folgt verwaltet:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li><strong>Währungsumrechnung:</strong> 1 CHF entspricht immer 1 Guthaben</li>
                <li><strong>Verwendung:</strong> Guthaben können ausschließlich auf teilnehmenden Partner-Plattformen und in Geschäften verwendet werden, die adwallet als Zahlungsmethode akzeptieren</li>
                <li><strong>Prepaid-System:</strong> Guthaben müssen vor der Verwendung aufgeladen werden; Überziehungen sind nicht möglich</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">3.3 Nicht übertragbar und nicht erstattungsfähig</h3>
            <p class="text-slate-700 leading-relaxed">
                Erworbene Guthaben sind nicht auf andere Konten übertragbar und grundsätzlich nicht erstattungsfähig, es sei denn, gesetzliche Bestimmungen (z.B. Widerrufsrecht) verlangen eine Rückerstattung.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">4. Zahlungsabwicklung</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.1 Zahlungsdienstleister</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Alle Zahlungsvorgänge werden über <strong>Stripe Payments Europe, Ltd.</strong> abgewickelt. Bei der Zahlung gelten die AGB und Datenschutzbestimmungen von Stripe.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.2 Zahlungsmethoden</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir akzeptieren alle von Stripe unterstützten Zahlungsmethoden, einschließlich Kreditkarten, Debitkarten und anderen lokalen Zahlungsmethoden.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">4.3 Zahlungsausfälle</h3>
            <p class="text-slate-700 leading-relaxed">
                Bei fehlgeschlagenen Zahlungen wird kein Guthaben gutgeschrieben. Wir sind nicht verantwortlich für Zahlungsausfälle aufgrund von Problemen mit Ihrer Zahlungsmethode oder Ihrem Finanzinstitut.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">5. Partner-Integrationen</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Partner-Plattformen integrieren adwallet über:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Signierte API-Aufrufe für Checkout-Initiiierung</li>
                <li>SSO-Authentifizierung mit signierten Tokens</li>
                <li>HMAC-signierte Webhooks für Transaktionsbenachrichtigungen</li>
            </ul>
            <p class="text-slate-700 leading-relaxed">
                Separate kommerzielle Vereinbarungen und Inhaltsrichtlinien gelten zwischen adwallet und jedem Partner. adwallet übernimmt keine Verantwortung für Inhalte, Angebote oder Dienstleistungen, die von Partnern bereitgestellt werden.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">6. Verfügbarkeit und Haftung</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">6.1 Verfügbarkeit</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir bemühen uns um eine hohe Verfügbarkeit des Dienstes, können jedoch keine Garantie für ununterbrochenen Betrieb geben. Wartungsarbeiten können zu vorübergehenden Ausfällen führen.
            </p>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">6.2 Haftungsausschluss</h3>
            <p class="text-slate-700 leading-relaxed mb-4">
                adwallet haftet nicht für:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Verluste aufgrund von technischen Störungen außerhalb unseres Einflussbereichs</li>
                <li>Verfügbarkeit oder Funktionalität externer Zahlungsdienstleister</li>
                <li>Inhalte, Angebote oder Dienstleistungen von Partner-Plattformen</li>
                <li>Betrug oder Missbrauch durch Dritte</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 mb-3 mt-6">6.3 Haftungsbegrenzung</h3>
            <p class="text-slate-700 leading-relaxed">
                Unsere Haftung ist auf Vorsatz und grobe Fahrlässigkeit beschränkt. Die Haftung für leichte Fahrlässigkeit ist ausgeschlossen, soweit gesetzlich zulässig.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">7. Nutzerpflichten</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Als Nutzer verpflichten Sie sich:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                <li>Ihre Zugangsdaten sicher aufzubewahren und nicht an Dritte weiterzugeben</li>
                <li>adwallet nicht für illegale oder betrügerische Zwecke zu nutzen</li>
                <li>Uns unverzüglich über unbefugte Nutzung Ihres Kontos zu informieren</li>
                <li>Die Nutzungsbedingungen einzuhalten</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">8. Kündigung</h2>
            <p class="text-slate-700 leading-relaxed mb-4">
                Wir behalten uns vor, Konten zu sperren oder zu kündigen, wenn:
            </p>
            <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4 mb-6">
                <li>Sie gegen diese AGB verstoßen</li>
                <li>Betrug oder Missbrauch festgestellt wird</li>
                <li>Gesetzliche Bestimmungen dies erfordern</li>
            </ul>
            <p class="text-slate-700 leading-relaxed">
                Bei Kündigung werden verbleibende Guthaben gemäß gesetzlichen Bestimmungen behandelt. Gesetzliche Aufbewahrungspflichten bleiben bestehen.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">9. Änderungen der AGB</h2>
            <p class="text-slate-700 leading-relaxed">
                Wir behalten uns vor, diese AGB anzupassen. Wesentliche Änderungen werden Ihnen per E-Mail oder über unsere Website mitgeteilt. Bei fortgesetzter Nutzung nach Bekanntgabe gelten die geänderten Bedingungen als akzeptiert.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">10. Anwendbares Recht und Gerichtsstand</h2>
            <p class="text-slate-700 leading-relaxed">
                Diese AGB unterliegen schweizerischem Recht. Gerichtsstand ist der Sitz von adwallet, sofern gesetzlich zulässig. Für Verbraucher gelten die Bestimmungen des schweizerischen Konsumentenschutzgesetzes.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">11. Kontakt</h2>
            <p class="text-slate-700 leading-relaxed">
                Bei Fragen zu diesen AGB kontaktieren Sie uns unter 
                <a href="mailto:support@adwallet.ch" class="text-blue-600 hover:text-blue-700 underline">support@adwallet.ch</a>.
            </p>
        </section>

        <section class="mt-10 pt-6 border-t border-slate-200">
            <p class="text-sm text-slate-500">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </section>
    </div>
@endsection
