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
        Schema::create('weather_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('nws_id')->unique();
            $table->string('event');
            $table->string('severity')->nullable();
            $table->string('urgency')->nullable();
            $table->string('certainty')->nullable();
            $table->text('area_desc')->nullable();
            $table->string('headline')->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('effective_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('active');
            $table->json('geometry')->nullable();
            $table->boolean('is_announced')->default(false);
            $table->timestamps();

            $table->index(['event', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_alerts');
    }
};
