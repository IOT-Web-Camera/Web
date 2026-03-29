<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Camera extends Model
{
    protected $fillable = [
        'name',
        'label',
        'owner_id',
        'stream_user',
        'stream_pass',
        'is_active',
        'last_heartbeat'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_heartbeat' => 'datetime',
    ];

    // Relation : une caméra appartient à un user
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function up(): void
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->timestamp('last_heartbeat')->nullable();
        });
    }
}
