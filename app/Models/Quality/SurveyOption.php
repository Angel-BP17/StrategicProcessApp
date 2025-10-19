<?php

namespace App\Models\Quality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    use HasFactory;
    protected $table = 'survey_options';
    protected $fillable = ['survey_question_id', 'option_text', 'order'];
    public $timestamps = true; // O 'false' si no los creaste

    /**
     * Una opción pertenece a una pregunta.
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}