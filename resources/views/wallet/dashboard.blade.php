<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Adwallet – Wallet</title>
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
                <div class="badge">Wallet</div>
                <h1 class="title">Advertising credits</h1>
                <p class="subtitle">Manage your balance and see recent top‑ups across partner marketplaces.</p>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="label">Current balance</div>
                <div class="value-xl">{{ $account ? number_format($account->balance) : '0' }}</div>
                <div class="value-sm">1 CHF = 1 credit</div>
            </div>
            <div class="card card-muted">
                <div class="label">Account</div>
                <div class="value-sm">
                    @if($account)
                        {{ $account->email ?? 'Wallet user' }}<br>
                        Partner: {{ $account->partner }} · ID: {{ $account->partner_user_id }}
                    @else
                        Kein Wallet-Konto vorhanden
                    @endif
                </div>
            </div>
        </div>

        <div class="transactions">
            <div class="label" style="margin-bottom:0.5rem;">Recent activity</div>
            @if ($transactions->isEmpty())
                <p class="value-sm">No wallet activity yet. Top up credits from your partner platform to see them here.</p>
            @else
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Credits</th>
                        <th>Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                            <td>{{ ucfirst($tx->status) }}</td>
                            <td class="{{ $tx->credits > 0 ? 'amount-pos' : 'amount-neg' }}">
                                {{ $tx->credits > 0 ? '+' : '' }}{{ number_format($tx->credits) }}
                            </td>
                            <td class="balance-cell">
                                {{ $account ? number_format($account->balance) : '-' }}
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


