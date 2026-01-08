<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Ticket;

class UserTicketController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['bookings.tickets.booking.event']);

        $tickets = $user->bookings->flatMap->tickets;

        return view('tickets.index', [
            'tickets' => $tickets,
        ]);
    }

    public function download(Ticket $ticket)
    {
        $user = Auth::user();
        if ($ticket->booking->user_id !== $user->id) {
            abort(403);
        }

        $path = 'qr_codes/'.$ticket->qr_code.'.svg';
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path, 'ticket-'.$ticket->id.'.svg');
    }
}
