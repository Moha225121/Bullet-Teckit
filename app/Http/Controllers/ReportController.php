<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Show simple reports: tickets sold, remaining capacity.
     */
    public function index()
    {
        $events = Event::withSum('bookings', 'tickets_count')
            ->orderBy('event_date')
            ->get();

        $metrics = $events->map(function ($e) {
            $sold = (int) ($e->bookings_sum_tickets_count ?? 0);
            $remaining = max((int) $e->remaining_tickets, 0);
            return [
                'id' => $e->id,
                'title' => $e->title,
                'date' => $e->event_date,
                'venue' => $e->venue,
                'total' => (int) $e->total_tickets,
                'sold' => $sold,
                'remaining' => $remaining,
            ];
        });

        return view('reports.index', [
            'events' => $events,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Export CSV of per-event metrics.
     */
    public function export(Request $request): StreamedResponse
    {
        $events = Event::withSum('bookings', 'tickets_count')
            ->orderBy('event_date')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="event-reports.csv"',
        ];

        return response()->stream(function () use ($events) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Event', 'Date', 'Venue', 'Total', 'Sold', 'Remaining']);
            foreach ($events as $e) {
                $sold = (int) ($e->bookings_sum_tickets_count ?? 0);
                fputcsv($out, [
                    $e->title,
                    optional($e->event_date)->format('Y-m-d H:i'),
                    $e->venue,
                    (int) $e->total_tickets,
                    $sold,
                    max((int) $e->remaining_tickets, 0),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
}
