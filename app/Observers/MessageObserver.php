<?php

namespace App\Observers;

use App\Notifications\MentionedInMessage;

class MessageObserver
{
    public function created(Message $m)
    {
        preg_match_all('/@([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,})/i', $m->content ?? '', $matches);
        $emails = $matches[1] ?? [];
        if (!$emails)
            return;
        $users = User::whereIn('email', $emails)->get();
        foreach ($users as $u) {
            $u->notify(new MentionedInMessage($m));
        }
    }
}
