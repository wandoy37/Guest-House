<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = ['guest_id', 'room_id', 'checkin', 'checkout', 'day', 'total_payment', 'deposit', 'room_charge', 'status'];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }

    // Accessor untuk format date input - Safe version
    public function getCheckinFormatAttribute()
    {
        return $this->checkin ? Carbon::parse($this->checkin)->format('Y-m-d') : '';
    }

    public function getCheckoutFormatAttribute()
    {
        return $this->checkout ? Carbon::parse($this->checkout)->format('Y-m-d') : '';
    }
}
