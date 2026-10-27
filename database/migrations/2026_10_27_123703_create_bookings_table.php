<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('fields');
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('duration_hours');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();

            /**
             * Unique constraint to prevent double booking:
             * the same field cannot be booked at the same date and start_time.
             */
            $table->unique(['field_id', 'booking_date', 'start_time'], 'bookings_field_date_start_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
