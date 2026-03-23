<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function father()
    {
        return $this->belongsTo(Member::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Member::class, 'mother_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'father_id')
            ->orWhere('mother_id', $this->id);
    }

    public function marriagesAsHusband()
    {
        return $this->hasMany(Marriage::class, 'husband_id');
    }

    public function marriagesAsWife()
    {
        return $this->hasMany(Marriage::class, 'wife_id');
    }
}
