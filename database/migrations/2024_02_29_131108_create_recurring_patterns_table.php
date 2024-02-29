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
        Schema::create('recurring_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()
                ->constrained('events')
                ->cascadeOnDelete();
            $table->foreignId('recurring_type_id')->nullable()
                ->constrained('recurring_types')
                ->cascadeOnDelete();
            $table->smallInteger('separation_count')->default(0);
            $table->smallInteger('max_num_of_occurrences')->default(0);
            $table->smallInteger('day_of_week')->default(0);
            $table->smallInteger('week_of_month')->default(0);
            $table->smallInteger('month_of_year')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_patterns');
    }
};
