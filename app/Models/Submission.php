<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'submitted_data' => 'array',
    ];

    public function targetMember()
    {
        return $this->belongsTo(Member::class, 'target_member_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
