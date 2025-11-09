<?php

namespace GoogleCalendar;

use Google_Service_Meet;
use Illuminate\Support\Facades\Http;

class GoogleMeet
{
    public Google_Service_Meet $service;
    public string $calendarId;
    public string $timezone;
    public int $conferenceDataVersion = 0;

    public function __construct()
    {
        $this->service = GoogleFactory::createGoogleService();
    }

    public function meet($meetId, $email)
    {
        $meetSpaceId = $this->service->spaces->get('spaces/' . $meetId)->getName();
        $token = $this->client->fetchAccessTokenWithAssertion();
        $resp = Http::acceptJson()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token'],
            'X-Goog-FieldMask' => 'name,email,role,user',
        ])->post('https://meet.googleapis.com/v2beta/'.$meetSpaceId.'/members', [
            'email' => $email,
            'role' => 'COHOST'
        ]);

        if (!$resp->ok()) {
            throw new \RuntimeException($resp->body() . $resp->status());
        }

        return $resp->json();
    }
}
