<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center justify-between">
			<div>
				<p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Organizer</p>
				<h2 class="font-semibold text-2xl text-slate-900 leading-tight">Edit event</h2>
			</div>
			<a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Back to events</a>
		</div>
	</x-slot>

	<div class="bg-gradient-to-br from-slate-900 via-indigo-900 to-blue-800">
		<div class="max-w-4xl mx-auto px-4 py-10 text-white">
			<div class="rounded-2xl border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur">
				<h3 class="text-xl font-semibold">Refresh the details</h3>
				<p class="text-blue-100 mt-2">Update timing, venue, or capacity. Booking stays capped at two tickets per attendee.</p>
			</div>
		</div>
	</div>

	<div class="max-w-4xl mx-auto px-4 py-10">
		@if ($errors->any())
			<div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 shadow-sm">
				<div class="font-semibold">Please fix the following:</div>
				<ul class="list-disc pl-5 space-y-1 text-sm mt-2">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('events.update', $event) }}" class="space-y-6">
			@csrf
			@method('PUT')

			<div class="grid gap-6 md:grid-cols-2">
				<div class="space-y-2">
					<label class="text-sm font-semibold text-slate-800">Title</label>
					<input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200" />
				</div>
				<div class="space-y-2">
					<label class="text-sm font-semibold text-slate-800">Venue</label>
					<input type="text" name="venue" value="{{ old('venue', $event->venue) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200" />
				</div>
			</div>

			<div class="space-y-2">
				<label class="text-sm font-semibold text-slate-800">Description</label>
				<textarea name="description" rows="4" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200">{{ old('description', $event->description) }}</textarea>
			</div>

			<div class="grid gap-6 md:grid-cols-2">
				<div class="space-y-2">
					<label class="text-sm font-semibold text-slate-800">Event date &amp; time</label>
					<input type="datetime-local" name="event_date" value="{{ old('event_date', \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200" />
				</div>
				<div class="space-y-2">
					<label class="text-sm font-semibold text-slate-800">Total tickets</label>
					<input type="number" name="total_tickets" min="1" value="{{ old('total_tickets', $event->total_tickets) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200" />
				</div>
			</div>

			<div class="flex items-center justify-between">
				<p class="text-sm text-slate-600">Current availability: {{ $event->remaining_tickets }} of {{ $event->total_tickets }}.</p>
				<div class="flex gap-3">
					<a href="{{ route('events.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
					<button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-600/30 transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-lg">Save changes</button>
				</div>
			</div>
		</form>
	</div>
</x-app-layout>
