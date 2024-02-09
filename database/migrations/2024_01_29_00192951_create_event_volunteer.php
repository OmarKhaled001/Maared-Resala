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
        Schema::create('event_volunteer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->nullable()
            ->references('id')
            ->on('volunteers')
            ->onDelete('set null');
            $table->foreignId('event_id')->nullable()
            ->references('id')
            ->on('events')
            ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
