<?php

namespace GoogleCalendar;

use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\Events;
use Google_Service_Calendar;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Str;

class GoogleEvent extends GoogleCalendar
{
    public int $conferenceDataVersion = 0;
    public Event $event;

    public function __construct(Event $event)
    {
        parent::__construct();
        $this->event = $event;
    }

    public function setName($name): void
    {
        $this->event->summary = $name;
    }

    public function setStartTime($startTime): void
    {
        $startDateTime = new Google_Service_Calendar_EventDateTime();
        $startDateTime->setDateTime($startTime);
        $startDateTime->setTimeZone($this->timezone); // Replace with the desired time zone
        $this->event->setStart($startDateTime);
    }

    public function setEndTime($endTime): void
    {
        $endDateTime = new Google_Service_Calendar_EventDateTime();
        $endDateTime->setDateTime($endTime);
        $endDateTime->setTimeZone($this->timezone); // Replace with the desired time zone
        $this->event->setEnd($endDateTime);
    }


    public function setParams(array $params): void
    {
        foreach ($params as $key => $value) {
            if ($value) {
                $this->event->$key = $value;
            }
        }
    }

    public function hasMeet(): void
    {
        $conferenceData = new Google_Service_Calendar_ConferenceData();
        $conferenceData->setCreateRequest(new CreateConferenceRequest([
            'requestId' => Str::random(10),
            'conferenceSolutionKey' => [
                'type' => 'HangoutsMeet'
            ]
        ]));
        $this->event->setConferenceData($conferenceData);
        $this->conferenceDataVersion = 1;
    }

    public function setAttendees(array $attendees): void
    {
        if ($attendees) {
            $emails = [];
            foreach ($attendees as $attendee) {
                $emails[] = ['email' => $attendee];
            }
            $this->event->setAttendees($emails);
        }
    }

    public function getEvents(): Events
    {
        return $this->service->events->listEvents($this->calendarId);
    }

    public function getEvent($id): Event
    {
        return $this->service->events->get($this->calendarId, $id);
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }
    
    public function createEvent(): Event
    {
        return $this->service->events->insert($this->calendarId, $this->event, ['conferenceDataVersion' => $this->conferenceDataVersion]);
    }

    public function updateEvent($eventId): object
    {
        $this->service->events->update($this->calendarId, $eventId, $this->event, ['conferenceDataVersion' => $this->conferenceDataVersion]);
        return (object)['message' => 'Event updated successfully', 'status' => 200];
    }

    public function deleteEvent($eventId): object
    {
        $this->service->events->delete($this->calendarId, $eventId);
        return (object)['message' => 'Event deleted successfully', 'status' => 200];
    }
}
