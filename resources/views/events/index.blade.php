<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center justify-between">
			<div>
				<p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Upcoming & Live</p>
				<h2 class="font-semibold text-2xl text-slate-900 leading-tight">Events</h2>
				@auth
					<div class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
						@if(auth()->user()->role === 'admin') bg-amber-100 text-amber-800 border border-amber-200
						@elseif(auth()->user()->role === 'organizer') bg-emerald-100 text-emerald-800 border border-emerald-200
						@else bg-slate-100 text-slate-700 border border-slate-200 @endif">
						{{ ucfirst(auth()->user()->role) }} view
					</div>
				@endauth
			</div>
			@auth
				@if(in_array(auth()->user()->role, ['admin', 'organizer']))
					<div class="flex items-center gap-3">
						<a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:-translate-y-0.5 hover:shadow">Reports</a>
						<a href="{{ route('events.create') }}"
						   class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:shadow-xl">
							<span class="text-lg">+</span>
							<span>Create Event</span>
						</a>
					</div>
				@endif
			@endauth
		</div>
	</x-slot>

	<div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50">
		<div class="max-w-6xl mx-auto px-4 py-12 text-slate-900">
			<div class="grid gap-8 lg:grid-cols-[1.1fr,0.9fr] items-center">
				<div class="space-y-4">
					<p class="text-sm uppercase tracking-[0.25em] text-indigo-500">Bullet Ticket</p>
					<h1 class="text-3xl sm:text-4xl font-bold leading-tight">Find, host, and join events with frictionless mobile ticketing.</h1>
					<p class="text-slate-600 text-lg">Book up to two tickets per event. Every ticket ships with an instant QR code for seamless check-in.</p>
					<div class="flex flex-wrap gap-3">
						<span class="rounded-full bg-white px-4 py-2 text-sm font-semibold border border-slate-200 text-slate-800">Instant QR</span>
						<span class="rounded-full bg-white px-4 py-2 text-sm font-semibold border border-slate-200 text-slate-800">Live capacity</span>
						<span class="rounded-full bg-white px-4 py-2 text-sm font-semibold border border-slate-200 text-slate-800">Mobile-first UI</span>
					</div>
				</div>
				<div class="relative">
					<div class="absolute inset-0 rounded-2xl bg-indigo-100 blur-3xl opacity-70"></div>
					<div class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
						<div class="flex items-center justify-between text-sm text-slate-600">
							<span>Live availability</span>
							<span>{{ now()->format('M j, Y g:i A') }}</span>
						</div>
						<div class="mt-4 space-y-3">
							@forelse ($events as $event)
								<div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
									<div class="flex items-center justify-between">
										<div>
											<p class="text-sm text-slate-500">{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M j, g:i A') }}</p>
											<p class="text-base font-semibold text-slate-900">{{ $event->title }}</p>
										</div>
										<span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">{{ $event->remaining_tickets }} left</span>
									</div>
								</div>
							@empty
								<div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-6 text-center text-slate-500">
									No events yet. Create one to get started.
								</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="max-w-6xl mx-auto px-4 py-12 space-y-8">
		<div class="flex items-center justify-between">
			<div>
				<h3 class="text-xl font-semibold text-slate-900">All events</h3>
				<p class="text-slate-500">Browse and book. Limit two tickets per booking.</p>
			</div>
		</div>

		@if (session('success'))
			<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
				{{ session('success') }}
			</div>
		@endif

		<div class="grid gap-6 md:grid-cols-2">
			@forelse ($events as $event)
				<div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl">
					<div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-transparent to-slate-50 opacity-0 transition group-hover:opacity-100"></div>
					<div class="relative flex flex-col h-full p-6 space-y-4">
						<div class="flex items-start justify-between gap-3">
							<div>
								<p class="text-sm font-semibold text-indigo-700">{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M j, Y') }}</p>
								<h4 class="text-xl font-semibold text-slate-900">{{ $event->title }}</h4>
								<p class="text-sm text-slate-600">{{ $event->venue }}</p>
							</div>
							<span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">{{ $event->remaining_tickets }} / {{ $event->total_tickets }} left</span>
						</div>

						<p class="text-slate-700 leading-relaxed">{{ \Illuminate\Support\Str::limit($event->description, 160) }}</p>

						<div class="flex items-center gap-4 text-sm text-slate-600">
							<div class="flex items-center gap-2">
								<span class="h-2 w-2 rounded-full bg-emerald-500"></span>
								<span>{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('g:i A') }}</span>
							</div>
							<div class="flex items-center gap-2">
								<span class="h-2 w-2 rounded-full bg-indigo-500"></span>
								<span>{{ $event->venue }}</span>
							</div>
						</div>

						<form method="POST" action="{{ route('events.book', $event) }}" class="mt-auto flex flex-wrap items-center gap-3">
							@csrf
							<label class="text-sm font-medium text-slate-700">Tickets</label>
							<input type="number" name="tickets_count" min="1" max="2" value="1" class="w-20 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200" />
							<button type="submit" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-600/30 transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-lg disabled:opacity-60" @if($event->remaining_tickets <= 0) disabled @endif>
								Book &amp; get QR
							</button>
						</form>

						<div class="flex items-center justify-between pt-2 text-xs text-slate-500">
							<span>Created by #{{ $event->created_by }}</span>
							@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'organizer']))
								<div class="flex gap-3">
									<a href="{{ route('events.edit', $event) }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Edit</a>
								</div>
							@endif
						</div>
					</div>
				</div>
			@empty
				<div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-600">
					No events yet. <a href="{{ route('events.create') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Create your first event</a>.
				</div>
			@endforelse
		</div>
	</div>
</x-app-layout>
