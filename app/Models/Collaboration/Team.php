<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'description', 'members'];   // si tu tabla tiene JSON de miembros
    protected $casts = ['members' => 'array'];

    public function channels()
    {
        return $this->hasMany(Channel::class, 'team_id');
    }

    // helpers si usas JSON de miembros
    public function hasMember(int $userId)
    {
        return collect($this->members ?? [])->pluck('user_id')->contains($userId);
    }
    public function addMember(int $userId, string $role = 'member')
    {
        $m = collect($this->members ?? []);
        if (!$m->pluck('user_id')->contains($userId)) {
            $m->push(['user_id' => $userId, 'role' => $role]);
        }
        $this->members = $m->values()->all();
        $this->save();
    }
    public function removeMember(int $userId)
    {
        $this->members = collect($this->members ?? [])->reject(fn($x) => (int) $x['user_id'] === $userId)->values()->all();
        $this->save();
    }
}
