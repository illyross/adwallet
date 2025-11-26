<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Adwallet Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
            background: radial-gradient(circle at top left, #e0f2fe, #eef2ff 40%, #f9fafb 80%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .shell {
            width: 100%;
            max-width: 480px;
            padding: 1.5rem;
        }
        .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.12),
                0 1px 2px rgba(15, 23, 42, 0.08);
            padding: 1.75rem 1.75rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: #eff6ff;
            color: #1d4ed8;
        }
        .title {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: #0f172a;
            margin: 0.6rem 0 0.2rem;
        }
        .subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }
        .summary {
            margin-top: 1.4rem;
            padding: 0.9rem 1rem;
            border-radius: 0.9rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 0.75rem;
            align-items: center;
        }
        .summary-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.15rem;
        }
        .summary-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }
        .chips {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
            margin-top: 0.25rem;
        }
        .chip {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-weight: 500;
        }
        .actions {
            margin-top: 1.6rem;
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
            gap: 0.75rem;
            align-items: center;
        }
        button.primary {
            border: none;
            border-radius: 999px;
            padding: 0.75rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            box-shadow:
                0 14px 30px rgba(37, 99, 235, 0.25),
                0 0 0 1px rgba(15, 23, 42, 0.02);
            transition: transform 0.08s ease-out, box-shadow 0.08s ease-out, filter 0.08s ease-out;
            width: 100%;
        }
        button.primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
            box-shadow:
                0 18px 40px rgba(37, 99, 235, 0.28),
                0 0 0 1px rgba(15, 23, 42, 0.03);
        }
        button.primary:active {
            transform: translateY(0);
            box-shadow:
                0 10px 24px rgba(37, 99, 235, 0.22),
                0 0 0 1px rgba(15, 23, 42, 0.03);
        }
        .secure {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.75rem;
            color: #64748b;
        }
        .secure-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #22c55e;
        }
        .footer {
            margin-top: 1.1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .logo-pill {
            position: absolute;
            right: 1.5rem;
            top: 1.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(148, 163, 184, 0.3);
            font-size: 0.7rem;
            font-weight: 600;
            color: #0f172a;
        }
        .logo-dot {
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #facc15, #ea580c);
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.25);
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="card">
        <div class="logo-pill">
            <span class="logo-dot"></span>
            <span>adwallet</span>
        </div>

        <span class="badge">
            <span>Secure checkout</span>
        </span>

        <h1 class="title">Confirm your credit purchase</h1>
        <p class="subtitle">
            You are purchasing wallet credits via a neutral payment provider. Your card statement will not reference the originating marketplace.
        </p>

        <div class="summary">
            <div>
                <div class="summary-label">Credits</div>
                <div class="summary-value">
                    {{ number_format($transaction->credits) }} credits
                </div>
                <div class="chips">
                    <span class="chip">1 CHF = 1 credit</span>
                    <span class="chip">Ref {{ $transaction->reference }}</span>
                </div>
            </div>
            <div style="text-align:right;">
                <div class="summary-label">Total</div>
                <div class="summary-value">
                    {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
                </div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.15rem;">
                    Sichere Zahlung
                </div>
            </div>
        </div>

        <form method="POST" action="#">
            <div class="actions">
                <button type="button" class="primary" disabled>
                    <span>Continue to payment</span>
                </button>
                <div class="secure">
                    <span class="secure-dot"></span>
                    <span>256‑bit TLS verschlüsselt &amp; sicher.</span>
                </div>
            </div>
        </form>

        <div class="footer">
            By continuing you agree to the wallet terms and understand that credits are non-refundable where permitted by law.
        </div>
    </div>
</div>
</body>
</html>


