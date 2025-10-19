<?php

namespace App\Models\Quality;

use App\Models\User; // Importamos User
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAssignment extends Model
{
    use HasFactory;
    protected $table = 'survey_assignments'; // Nuevo nombre de tabla
    protected $fillable = [
        'survey_id',
        'user_id',
        'status',
        'assigned_at',
        'completed_at',
    ];
    public $timestamps = true; // O 'false' si no los creaste

    /**
     * Una asignación pertenece a una encuesta.
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Una asignación pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}