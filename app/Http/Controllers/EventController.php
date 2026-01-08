<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events (public & dashboard)
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->get();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'venue'         => 'required|string|max:255',
            'event_date'    => 'required|date|after:today',
            'total_tickets' => 'required|integer|min:1',
        ]);

        Event::create([
            'title'             => $request->title,
            'description'       => $request->description,
            'venue'             => $request->venue,
            'event_date'        => $request->event_date,
            'total_tickets'     => $request->total_tickets,
            'remaining_tickets'=> $request->total_tickets,
            'created_by'        => Auth::id(),
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'venue'         => 'required|string|max:255',
            'event_date'    => 'required|date|after:today',
            'total_tickets' => 'required|integer|min:1',
        ]);

        $event->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'venue'         => $request->venue,
            'event_date'    => $request->event_date,
            'total_tickets' => $request->total_tickets,
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
