<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAddonRequest;
use App\Http\Requests\Admin\UpdateAddonRequest;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(): View
    {
        return view('admin.addons.index', [
            'addons' => Addon::orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.addons.create');
    }

    public function store(StoreAddonRequest $request): RedirectResponse
    {
        Addon::create($request->safe()->merge([
            'is_active' => $request->boolean('is_active'),
        ])->all());

        return redirect()->route('admin.addons.index')->with('success', 'Item tambahan berhasil ditambahkan.');
    }

    public function edit(Addon $addon): View
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(UpdateAddonRequest $request, Addon $addon): RedirectResponse
    {
        $addon->update($request->safe()->merge([
            'is_active' => $request->boolean('is_active'),
        ])->all());

        return redirect()->route('admin.addons.index')->with('success', 'Item tambahan berhasil diperbarui.');
    }

    public function destroy(Addon $addon): RedirectResponse
    {
        if ($addon->bookings()->exists()) {
            return redirect()->route('admin.addons.index')
                ->with('error', 'Item sudah tercatat pada booking dan tidak dapat dihapus. Nonaktifkan item ini sebagai gantinya.');
        }

        $addon->delete();

        return redirect()->route('admin.addons.index')->with('success', 'Item tambahan berhasil dihapus.');
    }
}
