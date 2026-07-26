<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/** Master provinsi (platform-wide, Superadmin). */
class ProvinceController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isSuperadmin(), 403);
    }

    public function index()
    {
        $this->guard();

        return view('backend.superadmin.provinces.index', [
            'provinces' => Province::withCount('cities')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->guard();
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        Province::create(['name' => $data['name'], 'slug' => $this->uniqueSlug($data['name'])]);

        return back()->with('success', 'Provinsi ditambahkan.');
    }

    public function update(Request $request, Province $province)
    {
        $this->guard();
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        $province->update([
            'name' => $data['name'],
            'slug' => $data['name'] !== $province->name ? $this->uniqueSlug($data['name'], $province->id) : $province->slug,
        ]);

        return back()->with('success', 'Provinsi diperbarui.');
    }

    public function destroy(Province $province)
    {
        $this->guard();
        if ($province->cities()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: masih ada kota di provinsi ini.');
        }
        $province->delete();

        return back()->with('success', 'Provinsi dihapus.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'provinsi';
        $slug = $base;
        $i = 1;
        while (Province::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
