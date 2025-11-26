@extends('layouts.admin')

@section('title', 'SSO Events – adwallet admin')
@section('heading', 'SSO Events')
@section('subheading', 'Recent partner SSO redirects into adwallet')

@section('content')
    <table class="min-w-full text-xs text-slate-200">
        <thead class="bg-slate-900/80">
        <tr>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Time</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Partner</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">User ID</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Email</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Role</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Nonce</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">Expires</th>
            <th class="px-3 py-2 text-left font-medium tracking-[0.16em] uppercase text-slate-400">IP</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/80">
        @forelse($events as $event)
            <tr class="hover:bg-slate-900/70">
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->created_at?->format('Y-m-d H:i:s') }}</td>
                <td class="px-3 py-2">{{ $event->partner }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->partner_user_id }}</td>
                <td class="px-3 py-2">{{ $event->email }}</td>
                <td class="px-3 py-2">{{ $event->role }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-400">{{ \Illuminate\Support\Str::limit($event->nonce, 10) }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->expires_at?->format('Y-m-d H:i') }}</td>
                <td class="px-3 py-2 font-mono text-[11px] text-slate-300">{{ $event->ip_address }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-3 py-4 text-center text-slate-400">No SSO events recorded yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection


