<?php
// app/Models/ApprovalRule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 'min_amount', 'max_amount', 'approval_order'
    ];

    protected $casts = [
        'approval_order' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
