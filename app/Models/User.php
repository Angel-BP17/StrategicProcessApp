<?php

namespace App\Models;

use App\Models\Collaboration\Message;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Importamos los modelos con los que se va a relacionar
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Graduate;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * CORREGIDO: Atributos que se pueden asignar masivamente.
     * Coincide con tu tabla 'users'.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'password',
        'role',
        // ... puedes añadir otros campos si es necesario
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'array',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function hasAnyRole(array $roles): bool
    {
        $own = collect($this->role ?? [])->map(fn($r) => strtolower((string) $r));
        return $own->intersect(collect($roles)->map(fn($r) => strtolower($r)))->isNotEmpty();
    }

    // --- RELACIONES AÑADIDAS ---

    /**
     * Relación: Un Usuario tiene un registro de Estudiante.
     */
    public function studentProfile()
    {
        // Asume que tu modelo Student está en App\Models\Student
        // y que la FK en la tabla 'students' es 'user_id'
        return $this->hasOne(Student::class, 'user_id');
    }

    /**
     * Relación: Un Usuario tiene un registro de Instructor.
     */
    public function instructorProfile()
    {
        // Asume que tu modelo Instructor está en App\Models\Instructor
        // y que la FK en la tabla 'instructors' es 'user_id'
        return $this->hasOne(Instructor::class, 'user_id');
    }

    /**
     * Relación: Un Usuario tiene un registro de Egresado.
     */
    public function graduateRecord()
    {
        // Asume que tu modelo Graduate está en App\Models\Graduate
        // y que la FK en la tabla 'graduates' es 'user_id'
        return $this->hasOne(Graduate::class, 'user_id');
    }

    public function messages(){
        return $this->hasMany(Message::class,'user_id');
    }
}