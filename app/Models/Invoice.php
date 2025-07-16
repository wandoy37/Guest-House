<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $fillable = [
        'booking_id',
        'user_id',
        'invoice_number',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Akses guest lewat booking
    public function guest()
    {
        return $this->hasOneThrough(Guest::class, Booking::class, 'id', 'id', 'booking_id', 'guest_id');
    }
}
