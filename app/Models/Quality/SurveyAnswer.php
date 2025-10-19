<?php

namespace App\Models\Quality;

use App\Models\User; // Importamos User
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;
    protected $table = 'survey_answers';
    protected $fillable = [
        'survey_question_id',
        'user_id',
        'survey_option_id',
        'answer_text',
        'answer_rating',
        'answered_at',
    ];
    public $timestamps = true; // O 'false' si no los creaste

    /**
     * Una respuesta pertenece a una pregunta.
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * Una respuesta pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una respuesta (opcionalmente) pertenece a una opción.
     */
    public function option()
    {
        return $this->belongsTo(SurveyOption::class, 'survey_option_id');
    }
}