<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'name',
        'type',
        'contact_phone',
        'contact_email',
    ];

    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }
}