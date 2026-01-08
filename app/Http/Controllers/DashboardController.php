<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['bookings.tickets.booking.event']);

        $bookings = $user->bookings->sortByDesc('created_at');
        $tickets = $bookings->flatMap->tickets;

        return view('dashboard', [
            'bookings' => $bookings,
            'tickets' => $tickets,
        ]);
    }
}
