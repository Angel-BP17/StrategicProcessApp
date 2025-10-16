<?php

namespace App\Models\Planning;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable = ['plan_id', 'title', 'owner_user_id', 'widgets'];
    protected $casts = ['widgets' => 'array'];
    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id');
    }
}