<?php

namespace App\Models\Quality;


use App\Models\Quality\CorrectiveAction; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;

    const CREATED_AT = null;

    protected $fillable = [
        'audit_id',
        'description',
        'classification',
        'evidence',
        'severity',
        'discovery_date',
    ];

    
    public function correctiveActions()
    {
        return $this->hasMany(CorrectiveAction::class);
    }
}