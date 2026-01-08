<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private QrCodeService $qrCodes)
    {
    }

    /**
     * Store a new booking for an event with a 2-ticket limit.
     */
    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'tickets_count' => 'required|integer|min:1|max:2',
        ]);

        $userId = Auth::id();

        try {
            DB::transaction(function () use ($event, $data, $userId) {
                $lockedEvent = Event::whereKey($event->id)->lockForUpdate()->first();

                // Lock existing bookings for this user & event to enforce per-fan cap.
                $existingBookings = Booking::where('event_id', $event->id)
                    ->where('user_id', $userId)
                    ->lockForUpdate()
                    ->get();

                $alreadyBooked = $existingBookings->sum('tickets_count');
                $requested = $data['tickets_count'];

                if ($alreadyBooked >= 2 || ($alreadyBooked + $requested) > 2) {
                    throw ValidationException::withMessages([
                        'tickets_count' => 'Limit reached: max 2 tickets per fan for this event.',
                    ]);
                }

                if ($lockedEvent->remaining_tickets < $data['tickets_count']) {
                    throw ValidationException::withMessages([
                        'tickets_count' => 'Not enough tickets remaining for this event.',
                    ]);
                }

                $booking = Booking::create([
                    'user_id'       => $userId,
                    'event_id'      => $lockedEvent->id,
                    'tickets_count' => $data['tickets_count'],
                ]);

                $tickets = [];
                for ($i = 0; $i < $data['tickets_count']; $i++) {
                    $ticket = Ticket::create([
                        'booking_id' => $booking->id,
                        'qr_code'    => (string) Str::uuid(),
                    ]);

                    $this->qrCodes->generateAndStore($ticket->qr_code);
                    $tickets[] = $ticket;
                }

                $lockedEvent->decrement('remaining_tickets', $data['tickets_count']);
            });
        } catch (ValidationException $e) {
            throw $e;
        }

        return Redirect::route('events.index')->with('success', 'Booking created successfully.');
    }
}
