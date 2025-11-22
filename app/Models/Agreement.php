<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'start_date',
        'renewal_date',
        'purpose',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}