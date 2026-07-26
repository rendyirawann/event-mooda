<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/** Master kategori event (platform-wide, Superadmin). */
class EventCategoryController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);
    }

    public function index()
    {
        $this->guard();

        return view('backend.superadmin.event-categories.index', [
            'categories' => EventCategory::withCount('events')
                ->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->guard();
        $data = $this->validated($request);
        $data['slug']       = $this->uniqueSlug($data['name']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? (EventCategory::max('sort_order') + 1));
        $data['is_active']  = $request->boolean('is_active', true);
        EventCategory::create($data);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, EventCategory $category)
    {
        $this->guard();
        $data = $this->validated($request);
        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? $category->sort_order);
        $data['is_active']  = $request->boolean('is_active', true);
        $category->update($data);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function toggle(EventCategory $category)
    {
        $this->guard();
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'Status kategori diubah.');
    }

    public function destroy(EventCategory $category)
    {
        $this->guard();
        if ($category->events()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: masih ada event pada kategori ini.');
        }
        $category->delete();

        return back()->with('success', 'Kategori dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'icon'       => ['nullable', 'string', 'max:32'],
            'color'      => ['nullable', 'string', 'max:40', 'regex:/^#?[0-9A-Fa-f]{3,8},\s*#?[0-9A-Fa-f]{3,8}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], ['color.regex' => 'Format warna: dua kode hex dipisah koma, mis. #7c3aed,#ec4899']);
        $data['color'] = $data['color'] ? str_replace(' ', '', $data['color']) : '#7c3aed,#ec4899';

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 1;
        while (EventCategory::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
