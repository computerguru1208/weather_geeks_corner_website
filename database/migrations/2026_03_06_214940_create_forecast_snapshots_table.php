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
        Schema::create('forecast_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->decimal('latitude', 8, 4);
            $table->decimal('longitude', 8, 4);
            $table->string('forecast_period_name')->nullable();
            $table->string('short_forecast')->nullable();
            $table->integer('temperature')->nullable();
            $table->string('temperature_unit')->nullable();
            $table->string('wind_speed')->nullable();
            $table->string('wind_direction')->nullable();
            $table->integer('humidity')->nullable();
            $table->decimal('dewpoint_f', 5, 1)->nullable();
            $table->timestamp('forecast_updated_at')->nullable();
            $table->string('hash')->nullable();
            $table->timestamps();

            $table->index('city');
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecast_snapshots');
    }
};
