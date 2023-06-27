<?php

namespace GoogleCalendar;

use Google_Service_Calendar;

class GoogleCalendar
{
    public Google_Service_Calendar $service;
    public string $calendarId;
    public string $timezone;
    public int $conferenceDataVersion = 0;

    public function __construct()
    {
        $this->service = GoogleCalendarFactory::createGoogleService();
        $this->calendarId = GoogleCalendarFactory::getPrimaryCalendarId();
        $this->timezone = GoogleCalendarFactory::getPrimaryTimezone();
    }

    public function setCalendarId($calendarId): void
    {
        $this->calendarId = $calendarId;
    }

    public function setTimezone($timezone): void
    {
        $this->timezone = $timezone;
    }
}
