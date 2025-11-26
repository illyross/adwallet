<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Adwallet – Guthaben</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f172a;
            color: #0f172a;
        }
        .shell {
            min-height: 100vh;
            background: radial-gradient(circle at top left, #1d4ed8, #0f172a 55%);
            padding: 2.5rem 1.5rem;
            display: flex;
            justify-content: center;
        }
        .frame {
            width: 100%;
            max-width: 960px;
            background: rgba(15, 23, 42, 0.96);
            border-radius: 1.75rem;
            box-shadow:
                0 30px 80px rgba(15, 23, 42, 0.7),
                0 0 0 1px rgba(148, 163, 184, 0.35);
            padding: 1.75rem 2rem 2rem;
            color: #e5e7eb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            color: #cbd5f5;
        }
        .title {
            font-size: 1.55rem;
            font-weight: 650;
            letter-spacing: -0.03em;
            margin: 0.3rem 0 0.15rem;
            color: #f9fafb;
        }
        .subtitle {
            font-size: 0.9rem;
            color: #9ca3af;
            margin: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            gap: 1.25rem;
            margin-top: 1.75rem;
        }
        .card {
            border-radius: 1.25rem;
            padding: 1.1rem 1.25rem;
            background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.22), rgba(15, 23, 42, 0.98));
            border: 1px solid rgba(148, 163, 184, 0.35);
        }
        .card-muted {
            background: rgba(15, 23, 42, 0.9);
        }
        .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }
        .value-xl {
            font-size: 2.1rem;
            font-weight: 700;
            color: #f9fafb;
        }
        .value-sm {
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .transactions {
            margin-top: 1.75rem;
            border-radius: 1.25rem;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(55, 65, 81, 0.9);
            padding: 1.25rem 1.5rem 1.1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        th, td {
            padding: 0.55rem 0.25rem;
            text-align: left;
        }
        th {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #9ca3af;
            border-bottom: 1px solid rgba(55, 65, 81, 0.9);
        }
        tr + tr td {
            border-top: 1px solid rgba(31, 41, 55, 0.9);
        }
        .amount-pos {
            color: #4ade80;
            font-weight: 600;
            text-align: right;
        }
        .amount-neg {
            color: #f97373;
            font-weight: 600;
            text-align: right;
        }
        .balance-cell {
            text-align: right;
            color: #e5e7eb;
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="frame">
        <div class="header">
            <div>
                <div class="badge">Guthaben</div>
                <h1 class="title">Werbe-Guthaben</h1>
                <p class="subtitle">Verwalten Sie Ihr Guthaben und sehen Sie aktuelle Aufladungen über Partner-Plattformen.</p>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                @if(session('wallet.redirect_back_url'))
                    <a href="{{ session('wallet.redirect_back_url') }}" style="background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 0.5rem; padding: 0.5rem 1rem; color: #93c5fd; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='rgba(59, 130, 246, 0.3)'; this.style.borderColor='rgba(59, 130, 246, 0.5)';" onmouseout="this.style.background='rgba(59, 130, 246, 0.2)'; this.style.borderColor='rgba(59, 130, 246, 0.35)';">
                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Zurück zur Plattform
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: rgba(148, 163, 184, 0.2); border: 1px solid rgba(148, 163, 184, 0.35); border-radius: 0.5rem; padding: 0.5rem 1rem; color: #cbd5f5; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(148, 163, 184, 0.3)'; this.style.borderColor='rgba(148, 163, 184, 0.5)';" onmouseout="this.style.background='rgba(148, 163, 184, 0.2)'; this.style.borderColor='rgba(148, 163, 184, 0.35)';">
                        Abmelden
                    </button>
                </form>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="label">Aktuelles Guthaben</div>
                <div class="value-xl">{{ $account ? number_format($account->balance) : '0' }}</div>
                <div class="value-sm">1 CHF = 1 Guthaben</div>
            </div>
            <div class="card card-muted">
                <div class="label">Konto</div>
                <div class="value-sm">
                    @if($account)
                        {{ $account->email ?? 'Guthaben-Benutzer' }}<br>
                        Partner: {{ $account->partner }} · ID: {{ $account->partner_user_id }}
                    @else
                        Kein Guthaben-Konto vorhanden
                    @endif
                </div>
            </div>
        </div>

        <div class="transactions">
            <div class="label" style="margin-bottom:0.5rem;">Aktuelle Aktivität</div>
            @if ($transactions->isEmpty())
                <p class="value-sm">Noch keine Guthaben-Aktivität. Laden Sie Guthaben über Ihre Partner-Plattform auf, um sie hier zu sehen.</p>
            @else
                <table>
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Typ</th>
                        <th>Guthaben</th>
                        <th>Saldo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at?->format('d.m.Y H:i') }}</td>
                            <td>
                                @if($tx->isDebit())
                                    Belastung
                                @elseif($tx->isCredit())
                                    Gutschrift
                                @else
                                    {{ ucfirst($tx->status) }}
                                @endif
                            </td>
                            <td class="{{ $tx->isCredit() ? 'amount-pos' : 'amount-neg' }}">
                                {{ $tx->isCredit() ? '+' : '-' }}{{ number_format($tx->credits) }}
                            </td>
                            <td class="balance-cell">
                                {{ $tx->balance_after !== null ? number_format($tx->balance_after) : ($account ? number_format($account->balance) : '-') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
</body>
</html>


