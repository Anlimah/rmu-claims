<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id', 
        'lecture_date', 
        'programme', 
        'course', 
        'start_time', 
        'end_time', 
        'duration', 
        'rate', 
        'subtotal'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
