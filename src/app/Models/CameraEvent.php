<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraEvent extends Model
{
    protected $fillable = [
        'device',
        'type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
