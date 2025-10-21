<?php

namespace App\Models\Collaboration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    public $timestamps = false;
    protected $fillable = ['task_id', 'user_id', 'assigned_by_user_id', 'assigned_at'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
