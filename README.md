# Laravel Google Calendar API Package

This package provides a convenient way to interact with the Google Calendar API in Laravel applications. It allows you to create, update, delete, and retrieve events from a Google Calendar.

## Installation
You can install this package via Composer by running the following command:
>
> composer require mhsaheb/google-calendar
>

## Configuration

>
> php artisan vendor:publish --tag=config
>

This command will create a google-calendar.php file in your config directory. Open the file and provide your Google API credentials and other required information.

## Usage

To use this package, you need to create an instance of the GoogleEvent class, which extends the GoogleCalendar class. Here's an example of how to create a new event:

``` 
use GoogleCalendar\GoogleEvent;

$event = new GoogleEvent();

// Set event details
$event->setName('My Event');
$event->setStartTime('2023-06-28T10:00:00');
$event->setEndTime('2023-06-28T12:00:00');
$event->setParams([
    'description' => 'This is a sample event',
    'location' => 'New York',
]);

// Add attendees
$event->setAttendees(['attendee1@example.com', 'attendee2@example.com']);

// Enable Google Meet for the event
$event->hasMeet();

// Create the event
$createdEvent = $event->createEvent();
```

You can also retrieve a list of events or get a specific event using the following methods:

```
// Get all events
$events = $event->getEvents();

// Get a specific event by ID
$eventId = 'your-event-id';
$event = $event->getEvent($eventId);
```

To update or delete an event, you can use the respective methods:

```
// Update an event
$eventId = 'your-event-id';
$updatedEvent = $event->updateEvent($eventId);

// Delete an event
$eventId = 'your-event-id';
$deletedEvent = $event->deleteEvent($eventId);
```

Please note that you need to set up your Google Calendar API credentials correctly in the configuration file for these methods to work.

### Using Google Service Account

If you want to use the package with a Google Service Account, you need to follow these additional steps:
1. Make sure you have a Google Workspace (formerly G Suite) account and access to the Google Console.
2. Activate the Domain-wide Delegation for your service account:

   * Go to the Google Cloud Console.
   * Select your project and navigate to the "IAM & Admin" -> "Service Accounts" section.
   * Find your service account and click on the "Edit" button.
   * Enable the "Enable Domain-wide Delegation" option.
   * Save the changes.
3. Obtain the client_email from your service account JSON key file.
4. Activate the Domain-wide Delegation Policy:
   * Go to your Google Workspace Admin Console.
   * Navigate to "Security" -> "API Controls" -> "Domain-wide Delegation".
   * Click on "Add new" and enter your client_id.
   * Specify the scopes you want to grant access to (e.g., https://www.googleapis.com/auth/cloud-platform).
   * Save the changes.
5. In your .env file, add the following variable with your client_email:
> 
> GOOGLE_CALENDAR_IMPERSONATE=your-service-account-email@example.com
>

## Contributing

If you encounter any issues or have suggestions for improvements, feel free to open an issue or submit a pull request on GitHub. We appreciate your contributions!

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/license/mit/).


