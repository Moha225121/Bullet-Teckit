<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">My Tickets</p>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight">Your QR passes</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Back to events</a>
        </div>
    </x-slot>

    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50">
        <div class="max-w-6xl mx-auto px-4 py-10 text-slate-900">
            <p class="text-slate-600">Present these QR codes at the gate. Each code is single-use and tied to its ticket.</p>
            @auth
                <div class="mt-3 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                    @if(auth()->user()->role === 'admin') bg-amber-100 text-amber-800 border border-amber-200
                    @elseif(auth()->user()->role === 'organizer') bg-emerald-100 text-emerald-800 border border-emerald-200
                    @else bg-white text-slate-800 border border-slate-200 @endif">
                    {{ ucfirst(auth()->user()->role) }} view
                </div>
            @endauth
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid gap-6 md:grid-cols-2">
            @forelse ($tickets as $ticket)
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-700 font-semibold">{{ $ticket->booking->event->title }}</p>
                            <p class="text-sm text-slate-600">{{ \Illuminate\Support\Carbon::parse($ticket->booking->event->event_date)->format('M j, Y g:i A') }}</p>
                            <p class="text-xs text-slate-500">Venue: {{ $ticket->booking->event->venue }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $ticket->is_used ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                            {{ $ticket->is_used ? 'Used' : 'Active' }}
                        </span>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url('qr_codes/'.$ticket->qr_code.'.svg') }}" alt="QR" class="h-36 w-36 object-contain" />
                        </div>
                        <div class="text-sm text-slate-700 break-all">
                            <p class="font-semibold text-slate-900">Ticket Code</p>
                            <p class="text-xs text-slate-500">Show this at the gate or share securely.</p>
                            <p class="mt-2 font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $ticket->qr_code }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-600">
                    You have no tickets yet. <a href="{{ route('events.index') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Browse events</a> to book.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
