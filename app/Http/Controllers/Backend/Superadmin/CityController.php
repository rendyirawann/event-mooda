<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Master kota + upload foto monumen (landmark_image). Platform-wide, Superadmin. */
class CityController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);
    }

    public function index()
    {
        $this->guard();

        return view('backend.superadmin.cities.index', [
            'cities'    => City::with('province')->withCount(['events'])
                ->orderBy('sort_order')->orderBy('name')->get(),
            'provinces' => Province::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->guard();
        $data = $this->validated($request);
        $data['slug']       = $this->uniqueSlug($data['name']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? (City::max('sort_order') + 1));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);
        if ($request->hasFile('landmark_image')) {
            $data['landmark_image'] = $request->file('landmark_image')->store('landmarks', 'public');
        }
        City::create($data);

        return back()->with('success', 'Kota ditambahkan.');
    }

    public function update(Request $request, City $city)
    {
        $this->guard();
        $data = $this->validated($request);
        if ($data['name'] !== $city->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $city->id);
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? $city->sort_order);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);

        if ($request->boolean('remove_image') && $city->landmark_image) {
            Storage::disk('public')->delete($city->landmark_image);
            $data['landmark_image'] = null;
        }
        if ($request->hasFile('landmark_image')) {
            if ($city->landmark_image) {
                Storage::disk('public')->delete($city->landmark_image);
            }
            $data['landmark_image'] = $request->file('landmark_image')->store('landmarks', 'public');
        }
        $city->update($data);

        return back()->with('success', 'Kota diperbarui.');
    }

    public function toggle(City $city)
    {
        $this->guard();
        $city->update(['is_featured' => ! $city->is_featured]);

        return back()->with('success', 'Status tampil di landing diubah.');
    }

    public function destroy(City $city)
    {
        $this->guard();
        if ($city->events()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: masih ada event di kota ini.');
        }
        if ($city->landmark_image) {
            Storage::disk('public')->delete($city->landmark_image);
        }
        $city->delete();

        return back()->with('success', 'Kota dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'province_id'    => ['required', 'exists:provinces,id'],
            'name'           => ['required', 'string', 'max:100'],
            'landmark_emoji' => ['nullable', 'string', 'max:16'],
            'color'          => ['nullable', 'string', 'max:40', 'regex:/^#?[0-9A-Fa-f]{3,8},\s*#?[0-9A-Fa-f]{3,8}$/'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'landmark_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1536'],
        ], [
            'color.regex'    => 'Format warna: dua hex dipisah koma, mis. #6366f1,#ec4899',
            'landmark_image.max' => 'Ukuran gambar maksimal 1.5MB.',
        ]);
        $data['color'] = $data['color'] ? str_replace(' ', '', $data['color']) : '#6366f1,#ec4899';
        unset($data['landmark_image']); // file gambar ditangani terpisah di store()/update()

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kota';
        $slug = $base;
        $i = 1;
        while (City::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
