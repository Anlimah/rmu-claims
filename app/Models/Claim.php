<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'submission_date', 'total_amount', 'status'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function details()
    {
        return $this->hasMany(ClaimDetail::class);
    }

    public function additionalExpenses()
    {
        return $this->hasMany(AdditionalExpense::class);
    }

    public function approvalWorkflows()
    {
        return $this->hasMany(ApprovalWorkflow::class);
    }
}
