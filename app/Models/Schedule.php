<?php

namespace App\Models;

use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['field_id', 'date', 'start_time', 'end_time', 'is_available'])]
class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Get the field this schedule belongs to.
     *
     * @return BelongsTo<Field, $this>
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get the booking associated with this schedule slot.
     *
     * @return HasOne<Booking, $this>
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }
}
