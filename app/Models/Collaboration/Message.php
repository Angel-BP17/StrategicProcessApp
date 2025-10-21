<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false; // la tabla suele tener solo created_at
    protected $fillable = ['channel_id', 'user_id', 'content', 'parent_id', 'pinned', 'created_at'];
    protected $casts = ['pinned' => 'boolean', 'created_at' => 'datetime'];

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
