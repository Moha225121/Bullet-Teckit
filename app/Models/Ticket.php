<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'qr_code',
        'is_used',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
