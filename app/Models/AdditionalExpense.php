<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalExpense extends Model
{
    use HasFactory;

    protected $fillable = ['claim_id', 'description', 'amount'];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
