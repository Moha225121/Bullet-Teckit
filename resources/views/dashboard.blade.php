<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Welcome</p>
                <div class="flex items-center gap-3">
                    <h2 class="font-semibold text-2xl text-slate-900 leading-tight">Your Dashboard</h2>
                    @auth
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            @if(auth()->user()->role === 'admin') bg-amber-100 text-amber-800 border border-amber-200
                            @elseif(auth()->user()->role === 'organizer') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    @endauth
                </div>
                <p class="text-sm text-slate-500">Tailored actions for your role.</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Browse events</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <div class="grid gap-8 lg:grid-cols-2">
                @auth
                    @if(in_array(auth()->user()->role, ['admin','organizer']))
                        <div class="bg-gradient-to-br from-emerald-600 to-indigo-700 text-white rounded-2xl shadow-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-emerald-100">Organizer mode</p>
                                    <h3 class="text-2xl font-semibold">Manage events</h3>
                                    <p class="text-emerald-50 mt-2">Create and edit events, and view live reports.</p>
                                </div>
                                <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Admin/Organizer</div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('events.create') }}" class="inline-flex items-center rounded-xl bg-white text-emerald-800 px-4 py-2 text-sm font-semibold shadow-sm hover:-translate-y-0.5 transition">Create event</a>
                                <a href="{{ route('reports.index') }}" class="inline-flex items-center rounded-xl border border-white/40 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 transition">View reports</a>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-indigo-600 to-slate-800 text-white rounded-2xl shadow-lg p-6">
                            <p class="text-sm uppercase tracking-[0.2em] text-indigo-100">Fan mode</p>
                            <h3 class="text-2xl font-semibold">Book and go</h3>
                            <p class="text-indigo-50 mt-2">Grab up to two tickets per event. Your QR codes are ready instantly.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('events.index') }}" class="inline-flex items-center rounded-xl bg-white text-indigo-800 px-4 py-2 text-sm font-semibold shadow-sm hover:-translate-y-0.5 transition">Find events</a>
                                <a href="{{ route('tickets.mine') }}" class="inline-flex items-center rounded-xl border border-white/40 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 transition">My tickets</a>
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- Booking History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-slate-900">Booking history</h3>
                        <p class="text-sm text-slate-600">Your recent bookings and ticket counts.</p>
                        <div class="mt-4 space-y-4">
                            @forelse ($bookings as $booking)
                                <div class="rounded-xl border border-slate-200 p-4 flex items-start justify-between">
                                    <div>
                                        <p class="text-sm text-indigo-700 font-semibold">{{ $booking->event->title }}</p>
                                        <p class="text-sm text-slate-600">{{ \Illuminate\Support\Carbon::parse($booking->event->event_date)->format('M j, Y g:i A') }}</p>
                                        <p class="text-xs text-slate-500">Venue: {{ $booking->event->venue }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold">{{ $booking->tickets_count }} ticket(s)</p>
                                        <p class="text-xs text-slate-500">Booked {{ $booking->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-600">No bookings yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Access to My Tickets -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-slate-900">Your QR tickets</h3>
                        <p class="text-sm text-slate-600">Present at the gate. Each QR is single-use.</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @forelse ($tickets as $ticket)
                                <div class="rounded-xl border border-slate-200 p-4 flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-indigo-700">{{ $ticket->booking->event->title }}</p>
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $ticket->is_used ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">{{ $ticket->is_used ? 'Used' : 'Active' }}</span>
                                    </div>
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url('qr_codes/'.$ticket->qr_code.'.svg') }}" alt="QR" class="h-28 w-28 object-contain rounded border border-slate-200 bg-white" />
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('tickets.download', $ticket) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Download</a>
                                        <a href="{{ route('tickets.mine') }}" class="text-sm text-slate-600 hover:text-slate-700">View all</a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-600">No tickets yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
