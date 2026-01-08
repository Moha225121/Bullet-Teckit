<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TicketValidationController extends Controller
{
    /**
     * Validate and redeem a ticket by QR code.
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $result = DB::transaction(function () use ($data) {
            $ticket = Ticket::where('qr_code', $data['qr_code'])
                ->lockForUpdate()
                ->with(['booking.event'])
                ->first();

            if (! $ticket) {
                throw ValidationException::withMessages([
                    'qr_code' => 'Ticket not found.',
                ]);
            }

            $event = $ticket->booking?->event;
            if (! $event) {
                throw ValidationException::withMessages([
                    'qr_code' => 'Ticket not linked to an event.',
                ]);
            }

            // Reject if event already started/past.
            if (now()->greaterThan($event->event_date)) {
                throw ValidationException::withMessages([
                    'qr_code' => 'Ticket expired for this event.',
                ]);
            }

            if ($ticket->is_used) {
                throw ValidationException::withMessages([
                    'qr_code' => 'Ticket already used.',
                ]);
            }

            $ticket->update(['is_used' => true]);

            return [
                'status' => 'valid',
                'ticket_id' => $ticket->id,
                'event' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'starts_at' => $event->event_date,
                ],
            ];
        });

        return response()->json($result);
    }
}
