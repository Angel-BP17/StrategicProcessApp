<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact',
        'legal_representative',
    ];

    protected $casts = [
        'contact' => 'array', // Guardar JSON con datos de contacto
    ];

    /**
     * Un socio puede tener muchos convenios
     */
    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }
}
