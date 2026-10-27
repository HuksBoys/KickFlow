<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FieldController extends Controller
{
    /**
     * Display a listing of all active fields.
     */
    public function index(Request $request): View
    {
        $query = Field::where('is_active', true);

        if ($request->filled('type') && $request->type !== 'semua') {
            $query->where('type', $request->type);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'harga_asc' => $query->orderBy('price_per_hour', 'asc'),
                'harga_desc' => $query->orderBy('price_per_hour', 'desc'),
                default => $query->orderBy('name', 'asc'),
            };
        } else {
            $query->orderBy('name', 'asc');
        }

        $fields = $query->paginate(9)->withQueryString();

        return view('fields.index', compact('fields'));
    }

    /**
     * Display the detail page for a specific field.
     */
    public function show(Field $field): View
    {
        abort_if(! $field->is_active, 404);

        return view('fields.show', compact('field'));
    }

    /**
     * Return available schedules for a field on a given date (API endpoint).
     */
    public function schedules(Request $request, Field $field): JsonResponse
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);

        $schedules = Schedule::where('field_id', $field->id)
            ->where('date', $request->date)
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get(['id', 'start_time', 'end_time', 'is_available']);

        return response()->json($schedules);
    }
}
