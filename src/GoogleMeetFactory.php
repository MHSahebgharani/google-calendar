<?php

namespace GoogleCalendar;

use Google_Client;
use Google_Service_Meet;
use GoogleCalendar\Exceptions\InvalidConfiguration;

class GoogleMeetFactory
{
    public static function createGoogleService(): Google_Service_Meet
    {
        $config = config('google-calendar');

        $client = self::createAuthenticatedGoogleClient($config);

        return new Google_Service_Meet($client);
    }

    public static function createGoogleServiceClient(): Google_Client
    {
        $config = config('google-calendar');

        return self::createAuthenticatedGoogleClient($config);
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

        throw InvalidConfiguration::invalidAuthenticationProfile($authProfile);
    }


    protected static function createServiceAccountClient(array $authProfile): Google_Client
    {
        $client = new Google_Client;

        $client->setScopes([
            'https://www.googleapis.com/auth/cloud-platform', 'https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/meetings.space.settings', 'https://www.googleapis.com/auth/meetings.space.readonly', 'https://www.googleapis.com/auth/meetings.space.created'
        ]);

        $client->setAuthConfig($authProfile['credentials_json']);

        if (config('google-calendar')['user_to_impersonate']) {
            $client->setSubject(config('google-calendar')['user_to_impersonate']);
        }

        return $client;
    }
}
