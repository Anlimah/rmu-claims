<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = ['claim_id', 'stage', 'approver_id', 'status', 'comments', 'approved_at'];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
