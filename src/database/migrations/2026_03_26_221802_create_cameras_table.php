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
        // create_cameras_table
        Schema::create('cameras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('label', 255)->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('stream_user', 50)->default('admin');
            $table->string('stream_pass', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_heartbeat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};
