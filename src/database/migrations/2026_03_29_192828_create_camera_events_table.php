<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('camera_events', function (Blueprint $table) {
            $table->id();
            $table->string('device');      // nom de la caméra
            $table->string('type');        // telemetry, LED_ACK, MOVE_ACK, etc.
            $table->json('payload');       // données envoyées par la caméra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camera_events');
    }
};
