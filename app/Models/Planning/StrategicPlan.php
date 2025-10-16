<?php

namespace App\Models\Planning;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StrategicPlan extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'created_by_user_id',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function objectives()
    {
        return $this->hasMany(StrategicObjective::class, 'plan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
