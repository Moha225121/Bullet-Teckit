<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Organizer / Admin</p>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight">Event Reports</h2>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.export') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Export CSV</a>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Back to events</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-10 space-y-10">
        <!-- Summary table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
            <div class="p-6">
                <h3 class="font-semibold text-lg text-slate-900">By Event</h3>
                <p class="text-sm text-slate-600">Tickets sold and remaining capacity.</p>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-slate-700">Event</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-700">Date</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-700">Venue</th>
                                <th class="px-4 py-2 text-right font-semibold text-slate-700">Total</th>
                                <th class="px-4 py-2 text-right font-semibold text-slate-700">Sold</th>
                                <th class="px-4 py-2 text-right font-semibold text-slate-700">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($metrics as $m)
                                <tr>
                                    <td class="px-4 py-2">{{ $m['title'] }}</td>
                                    <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($m['date'])->format('M j, Y g:i A') }}</td>
                                    <td class="px-4 py-2">{{ $m['venue'] }}</td>
                                    <td class="px-4 py-2 text-right">{{ $m['total'] }}</td>
                                    <td class="px-4 py-2 text-right">{{ $m['sold'] }}</td>
                                    <td class="px-4 py-2 text-right">{{ $m['remaining'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Simple charts -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
            <div class="p-6">
                <h3 class="font-semibold text-lg text-slate-900">Tickets Sold</h3>
                <p class="text-sm text-slate-600">Quick visual for sold vs capacity.</p>
                <div class="mt-6">
                    <canvas id="ticketsChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($metrics->pluck('title'));
        const sold = @json($metrics->pluck('sold'));
        const total = @json($metrics->pluck('total'));
        const ctx = document.getElementById('ticketsChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Sold', data: sold, backgroundColor: 'rgba(79, 70, 229, 0.7)' },
                    { label: 'Total', data: total, backgroundColor: 'rgba(203, 213, 225, 0.7)' },
                ]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>
