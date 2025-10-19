<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Agreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partner_id',
        'title',
        'start_date',
        'end_date',
        'status',
        'renewal_date',
        'electronic_signature',
        'created_by_user_id',
    ];

    protected $casts = [
        'electronic_signature' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
    ];

    /**
     * Un convenio pertenece a un socio
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relación opcional: usuario que creó el convenio
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}