<?php
namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleEvent;
use IncadevUns\CoreDomain\Models\StrategicPlan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GoogleCalendarService
{
    private GoogleCalendar $calendar;
    private string $calendarId;
    private string $tz;

    public function __construct(?string $calendarId = null, ?string $timezone = null)
    {
        $client = new GoogleClient();
        $client->setApplicationName('StrategicProcessApp');
        $client->setScopes([GoogleCalendar::CALENDAR]);
        $client->setAuthConfig(base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        // Impersonación opcional (Workspace)
        if (env('GOOGLE_IMPERSONATE_USER')) {
            $client->setSubject(env('GOOGLE_IMPERSONATE_USER'));
        }

        $this->calendar = new GoogleCalendar($client);
        $this->calendarId = $calendarId ?: env('GOOGLE_CALENDAR_ID');
        $this->tz = $timezone ?: config('app.timezone', 'America/Lima');
    }

    /**
     * Crea evento “all-day” del plan (start_date..end_date inclusive)
     * Nota: en Calendar las fechas all-day usan end EXCLUSIVO -> sumamos 1 día.
     */
    public function createEventForPlan(StrategicPlan $plan): GoogleEvent
    {
        $start = Carbon::parse($plan->start_date)->toDateString();
        $end = Carbon::parse($plan->end_date)->addDay()->toDateString();

        $summary = $plan->title;
        $description = strip_tags((string) $plan->description);

        $event = new GoogleEvent([
            'summary' => $summary,
            'description' => $description,
            'start' => ['date' => $start, 'timeZone' => $this->tz],
            'end' => ['date' => $end, 'timeZone' => $this->tz],
            // Recordatorios (opcional)
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 7 * 24 * 60], // 7 días antes
                    ['method' => 'popup', 'minutes' => 1 * 24 * 60], // 1 día antes
                ],
            ],
        ]);

        $created = $this->calendar->events->insert($this->calendarId, $event);
        return $created;
    }

    public function updateEventForPlan(StrategicPlan $plan): GoogleEvent
    {
        if (!$plan->google_event_id) {
            // Si no existía, lo creamos
            $created = $this->createEventForPlan($plan);
            $this->attachEventIds($plan, $created);
            return $created;
        }

        $start = Carbon::parse($plan->start_date)->toDateString();
        $end = Carbon::parse($plan->end_date)->addDay()->toDateString();

        $event = $this->calendar->events->get(
            $plan->google_calendar_id ?: $this->calendarId,
            $plan->google_event_id
        );

        $event->setSummary($plan->title);
        $event->setDescription(strip_tags((string) $plan->description));
        $event->setStart(['date' => $start, 'timeZone' => $this->tz]);
        $event->setEnd(['date' => $end, 'timeZone' => $this->tz]);

        $updated = $this->calendar->events->update(
            $plan->google_calendar_id ?: $this->calendarId,
            $plan->google_event_id,
            $event
        );

        return $updated;
    }

    public function deleteEventForPlan(StrategicPlan $plan): void
    {
        if (!$plan->google_event_id)
            return;

        $this->calendar->events->delete(
            $plan->google_calendar_id ?: $this->calendarId,
            $plan->google_event_id
        );
    }

    public function attachEventIds(StrategicPlan $plan, GoogleEvent $event): void
    {
        $plan->forceFill([
            'google_event_id' => $event->getId(),
            'google_calendar_id' => $this->calendarId,
        ])->save();
    }
}
