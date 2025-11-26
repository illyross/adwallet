@extends('layouts.admin')

@section('title', 'Webhooks – adwallet admin')
@section('heading', 'Partner Webhooks')
@section('subheading', 'Recent wallet.topup events sent to partners')

@section('content')
    <table class="min-w-full text-xs text-slate-200">
        <thead class="bg-slate-900/80">
        <tr>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Time</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Partner</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Txn ID</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Event</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Status</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">HTTP</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">URL</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/80">
        @forelse($events as $event)
            <tr class="hover:bg-slate-900/70">
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->created_at?->format('Y-m-d H:i:s') }}</td>
                <td class="px-3 py-2">{{ $event->partner }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->wallet_transaction_id }}</td>
                <td class="px-3 py-2">{{ $event->event }}</td>
                <td class="px-3 py-2">
                    @if($event->success)
                        <span class="inline-flex rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-medium text-emerald-300">
                            OK
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-medium text-rose-300">
                            Failed
                        </span>
                    @endif
                </td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->status_code ?? '—' }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-400">{{ \Illuminate\Support\Str::limit($event->url, 40) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-3 py-4 text-center text-slate-400">No webhook events recorded yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection


