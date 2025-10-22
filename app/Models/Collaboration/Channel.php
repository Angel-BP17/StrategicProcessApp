<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['team_id', 'name', 'related_plan_id', 'channel_type', 'description', 'topic', 'members', 'created_by_user_id'];
    protected $casts = ['members' => 'array'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'channel_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'channel_id');
    }

    // JSON helpers (si existen en tu esquema)
    public function roleOf(int $userId)
    {
        return collect($this->members ?? [])->firstWhere('user_id', $userId)['role'] ?? null;
    }
    public function isMember(int $userId)
    {
        return collect($this->members ?? [])->pluck('user_id')->contains($userId);
    }
    public function addMember(int $userId, string $role = 'member')
    {
        $m = collect($this->members ?? []);
        if (!$m->pluck('user_id')->contains($userId))
            $m->push(['user_id' => $userId, 'role' => $role]);
        $this->members = $m->values()->all();
        $this->save();
    }
    public function setRole(int $userId, string $role)
    {
        $this->members = collect($this->members ?? [])->map(function ($x) use ($userId, $role) {
            if ((int) $x['user_id'] === $userId) {
                $x['role'] = $role;
            }return $x;
        })->values()->all();
        $this->save();
    }
    public function removeMember(int $userId)
    {
        $this->members = collect($this->members ?? [])->reject(fn($x) => (int) $x['user_id'] === $userId)->values()->all();
        $this->save();
    }
}
