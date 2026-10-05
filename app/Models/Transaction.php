<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'waktu',
        'tipe',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}