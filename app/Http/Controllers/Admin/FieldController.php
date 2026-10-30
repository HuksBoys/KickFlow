<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFieldRequest;
use App\Http\Requests\Admin\UpdateFieldRequest;
use App\Models\Field;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FieldController extends Controller
{
    /**
     * Display a listing of all fields (active and inactive) for admin management.
     */
    public function index(Request $request): View
    {
        $query = Field::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('type') && $request->type !== 'semua') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $fields = $query->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new field.
     */
    public function create(): View
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created field in the database.
     */
    public function store(StoreFieldRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['image']);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('fields', 'public');
        }

        Field::create($validated);

        return redirect()->route('admin.fields.index')
            ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(Field $field): View
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified field in the database.
     */
    public function update(UpdateFieldRequest $request, Field $field): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['image']);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($field->image_path) {
                Storage::disk('public')->delete($field->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('fields', 'public');
        }

        $field->update($validated);

        return redirect()->route('admin.fields.index')
            ->with('success', 'Lapangan berhasil diperbarui!');
    }

    /**
     * Remove the specified field from the database.
     */
    public function destroy(Field $field): RedirectResponse
    {
        if ($field->image_path) {
            Storage::disk('public')->delete($field->image_path);
        }

        $field->delete();

        return redirect()->route('admin.fields.index')
            ->with('success', 'Lapangan berhasil dihapus!');
    }

    /**
     * Toggle the active status of the specified field.
     */
    public function toggleStatus(Field $field): RedirectResponse
    {
        $field->update(['is_active' => ! $field->is_active]);

        $status = $field->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.fields.index')
            ->with('success', "Lapangan berhasil {$status}!");
    }
}
