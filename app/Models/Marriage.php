<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function husband()
    {
        return $this->belongsTo(Member::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Member::class, 'wife_id');
    }
}
