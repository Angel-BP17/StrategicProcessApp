<?php

namespace App\Observers;

use App\Models\Collaboration\Message;
use App\Models\User;
use App\Notifications\MentionedInMessage;
use Log;

class MessageObserver
{
    private const MENTION_LIMIT = 10;

    public function created(Message $m): void
    {
        $content = (string) ($m->content ?? '');

        // 1) Extraer tokens: @Angel, @Bustamante, @Angel_Bustamante, @Angel-Bustamante, @Angel.Bustamante
        preg_match_all('/@([\p{L}]+(?:[._-][\p{L}]+)*)/u', $content, $matches);
        $tokens = collect($matches[1] ?? [])
            ->map(fn($t) => mb_strtolower(trim($t)))
            ->filter()
            ->unique()
            ->values();

        Log::debug('[MentionObserver] Tokens detectados', ['tokens' => $tokens->all()]);

        if ($tokens->isEmpty()) {
            Log::debug('[MentionObserver] No tokens.');
            return;
        }

        // 2) Miembros del canal (si 'members' es JSON string, decodificar)
        $rawMembers = $m->channel?->members ?? [];
        if (is_string($rawMembers)) {
            $decoded = json_decode($rawMembers, true);
            $rawMembers = is_array($decoded) ? $decoded : [];
        }

        $memberIds = collect($rawMembers)
            ->pluck('user_id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        Log::debug('[MentionObserver] memberIds', ['memberIds' => $memberIds->all()]);

        if ($memberIds->isEmpty()) {
            Log::debug('[MentionObserver] Canal sin miembros.');
            return;
        }

        $authorId = (int) $m->user_id;

        // 3) Cargar miembros y construir aliases (first, last, combinaciones)
        $members = User::query()->whereIn('id', $memberIds)->get();

        if ($members->isEmpty()) {
            Log::debug('[MentionObserver] Sin miembros en BD.');
            return;
        }

        $userAliases = $members->mapWithKeys(function (User $u) {
            $first = mb_strtolower(trim((string) ($u->first_name ?? '')));
            $last  = mb_strtolower(trim((string) ($u->last_name  ?? '')));

            $aliases = collect([$first, $last])
                ->merge($first && $last ? [
                    "{$first}_{$last}",
                    "{$first}-{$last}",
                    "{$first}.{$last}",
                    "{$first}{$last}",
                ] : [])
                ->filter()
                ->unique()
                ->values()
                ->all();

            return [$u->id => $aliases];
        });

        // Log de ejemplo (no imprimir todo para no saturar)
        Log::debug('[MentionObserver] Aliases ejemplo', $userAliases->take(3)->toArray());

        // 4) Selección correcta del user_id que matchea cada token (ARREGLO CLAVE)
        $selectedUserIds = collect(); // ids únicos en orden
        foreach ($tokens as $token) {
            // Antes: $userAliases->first(callback …) devolvía el array de aliases, no la key (bug).
            // Ahora: filtramos y tomamos la PRIMERA KEY (user_id).
            $matchId = $userAliases
                ->filter(fn(array $aliases, int $uid) =>
                    $uid !== $authorId && in_array($token, $aliases, true)
                )
                ->keys()
                ->first(); // <-- devuelve el user_id

            if ($matchId !== null && !$selectedUserIds->contains($matchId)) {
                $selectedUserIds->push($matchId);
                if ($selectedUserIds->count() >= self::MENTION_LIMIT) {
                    Log::warning('[MentionObserver] Límite de menciones alcanzado', [
                        'limit' => self::MENTION_LIMIT,
                        'selected' => $selectedUserIds->all(),
                    ]);
                    break;
                }
            }
        }

        Log::debug('[MentionObserver] selectedUserIds', ['selectedUserIds' => $selectedUserIds->all()]);

        if ($selectedUserIds->isEmpty()) {
            Log::debug('[MentionObserver] Sin coincidencias de menciones.');
            return;
        }

        // 5) Notificar
        $usersToNotify = $members->whereIn('id', $selectedUserIds);

        Log::debug('[MentionObserver] Notificando usuarios', [
            'user_ids'   => $usersToNotify->pluck('id')->all(),
            'message_id' => $m->id,
            'channel_id' => $m->channel_id,
        ]);

        foreach ($usersToNotify as $u) {
            $u->notify(new MentionedInMessage($m));
        }
    }
}
