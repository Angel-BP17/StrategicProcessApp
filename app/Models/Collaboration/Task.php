<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['channel_id', 'title', 'description', 'status', 'priority', 'due_date', 'created_by_user_id'];
    protected $casts = ['due_date' => 'date'];
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class, 'task_id');
    }
}
