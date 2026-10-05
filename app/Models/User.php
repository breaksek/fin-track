<?php

namespace App\Models;

// ... import bawaan Breeze ...
use App\Models\Transaction; // <-- Tambahkan import ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Relasi ke model Transaction (One-to-Many)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}