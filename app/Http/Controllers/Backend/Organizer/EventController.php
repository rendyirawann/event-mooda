<?php

namespace App\Http\Controllers\Backend\Organizer;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Dashboard organizer: buat & kelola event miliknya (Superadmin melihat semua). */
class EventController extends Controller
{
    private function guard(): void
    {
        abort_unless(
            Auth::check() && (Auth::user()->hasRole('organizer') || Auth::user()->isSuperadmin()),
            403,
            'Halaman ini khusus penyelenggara (organizer).'
        );
    }

    private function authorizeOwner(Event $event): void
    {
        abort_unless(Auth::user()->isSuperadmin() || $event->organizer_id === Auth::id(), 403);
    }

    public function index()
    {
        $this->guard();
        $query = Event::with(['category', 'city'])->withCount('ticketTypes');
        if (! Auth::user()->isSuperadmin()) {
            $query->where('organizer_id', Auth::id());
        }

        return view('backend.organizer.events.index', ['events' => $query->latest()->get()]);
    }

    public function create()
    {
        $this->guard();

        return view('backend.organizer.events.form', $this->formData(new Event(['status' => 'draft'])));
    }

    public function store(Request $request)
    {
        $this->guard();
        $data = $this->validated($request);
        $data['organizer_id'] = Auth::id();
        $data['slug']         = $this->uniqueSlug($data['title']);
        $data['status']       = 'draft';
        if ($request->hasFile('poster_image')) {
            $data['poster_image'] = $request->file('poster_image')->store('posters', 'public');
        }
        $event = Event::create($data);

        return redirect()->route('organizer.events.edit', $event)
            ->with('success', 'Event dibuat sebagai draft. Tambahkan jenis tiket, lalu publish.');
    }

    public function edit(Event $event)
    {
        $this->guard();
        $this->authorizeOwner($event);
        $event->load(['ticketTypes' => fn ($q) => $q->orderBy('sort_order')]);

        return view('backend.organizer.events.form', $this->formData($event));
    }

    public function update(Request $request, Event $event)
    {
        $this->guard();
        $this->authorizeOwner($event);
        $data = $this->validated($request);
        if ($data['title'] !== $event->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $event->id);
        }
        if ($request->boolean('remove_poster') && $event->poster_image) {
            Storage::disk('public')->delete($event->poster_image);
            $data['poster_image'] = null;
        }
        if ($request->hasFile('poster_image')) {
            if ($event->poster_image) {
                Storage::disk('public')->delete($event->poster_image);
            }
            $data['poster_image'] = $request->file('poster_image')->store('posters', 'public');
        }
        $event->update($data);

        return back()->with('success', 'Event diperbarui.');
    }

    public function togglePublish(Event $event)
    {
        $this->guard();
        $this->authorizeOwner($event);

        if ($event->status !== 'published') {
            if (! $event->ticketTypes()->where('is_active', true)->exists()) {
                return back()->with('error', 'Tambahkan minimal 1 jenis tiket aktif sebelum publish.');
            }
            $event->update(['status' => 'published']);

            return back()->with('success', 'Event dipublikasikan — kini tampil di landing.');
        }

        $event->update(['status' => 'draft']);

        return back()->with('success', 'Event dijadikan draft (disembunyikan dari landing).');
    }

    public function destroy(Event $event)
    {
        $this->guard();
        $this->authorizeOwner($event);
        if ($event->poster_image) {
            Storage::disk('public')->delete($event->poster_image);
        }
        $event->delete(); // ticket_types & tickets ikut terhapus (cascade FK)

        return redirect()->route('organizer.events.index')->with('success', 'Event dihapus.');
    }

    private function formData(Event $event): array
    {
        return [
            'event'      => $event,
            'categories' => EventCategory::active()->ordered()->get(),
            'cities'     => City::where('is_active', true)->with('province')->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:150'],
            'event_category_id' => ['required', 'exists:event_categories,id'],
            'city_id'           => ['required', 'exists:cities,id'],
            'tagline'           => ['nullable', 'string', 'max:200'],
            'description'       => ['nullable', 'string', 'max:8000'],
            'venue_name'        => ['nullable', 'string', 'max:150'],
            'venue_address'     => ['nullable', 'string', 'max:255'],
            'starts_at'         => ['nullable', 'date'],
            'ends_at'           => ['nullable', 'date', 'after_or_equal:starts_at'],
            'poster_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], ['poster_image.max' => 'Ukuran poster maksimal 2MB.']);
        unset($data['poster_image']); // file ditangani terpisah

        return $data;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'event';
        $slug = $base;
        $i = 1;
        while (Event::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
