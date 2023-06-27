<?php

namespace GoogleCalendar;

use Google_Client;
use Google_Service_Calendar;
use GoogleCalendar\Exceptions\InvalidConfiguration;

class GoogleCalendarFactory
{
    public static function createGoogleService(): Google_Service_Calendar
    {
        $config = config('google-calendar');

        $client = self::createAuthenticatedGoogleClient($config);

        return new Google_Service_Calendar($client);
    }

    public static function getPrimaryCalendarId(): string
    {
        $config = config('google-calendar');

        if ($config['calendar_id']) {
            return $config['calendar_id'];
        }

        throw InvalidConfiguration::calendarIdNotSpecified();
    }
    public static function getPrimaryTimezone(): string
    {
        $config = config('app');

        if ($config['timezone']) {
            return $config['timezone'];
        }

        throw InvalidConfiguration::calendarIdNotSpecified();
    }

    public static function createAuthenticatedGoogleClient(array $config): Google_Client
    {
        $authProfile = $config['default_auth_profile'];

        if ($authProfile === 'service_account') {
            return self::createServiceAccountClient($config['auth_profiles']['service_account']);
        }
        if ($authProfile === 'oauth') {
            return self::createOAuthClient($config['auth_profiles']['oauth']);
        }

        throw InvalidConfiguration::invalidAuthenticationProfile($authProfile);
    }


    protected static function createServiceAccountClient(array $authProfile): Google_Client
    {
        $client = new Google_Client;

        $client->setScopes([
            'https://www.googleapis.com/auth/cloud-platform', 'https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/calendar', 'https://www.googleapis.com/auth/calendar.events'
        ]);

        $client->setAuthConfig($authProfile['credentials_json']);

        if (config('google-calendar')['user_to_impersonate']) {
            $client->setSubject(config('google-calendar')['user_to_impersonate']);
        }

        return $client;
    }

    protected static function createOAuthClient(array $authProfile): Google_Client
    {
        $client = new Google_Client;

        $client->setScopes([
            Google_Service_Calendar::CALENDAR,
        ]);

        $client->setAuthConfig($authProfile['credentials_json']);

        $client->setAccessToken(file_get_contents($authProfile['token_json']));

        return $client;
    }

}
