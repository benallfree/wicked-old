<?php
require_once '../../src/apiClient.php';
require_once '../../src/contrib/apiCalendarService.php';
session_start();

$client = new apiClient();
$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('1005298202201-4pf0ta5q69sgkv86ouukbrpnhasm2q48.apps.googleusercontent.com');
$client->setClientSecret('qhxqGTtGYntO1VqwmRcXL0Ji');
$client->setRedirectUri('http://coaching.benallfree.com/google-api-php-client/examples/calendar/simple.php');
$client->setDeveloperKey('AIzaSyBdVO1ZUi49sUtvRJeMeY_64rylrOqCIzU');
$cal = new apiCalendarService($client);
if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  $calList = $cal->calendarList->listCalendarList();
  print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";

  $event = new Event();
  $event->setSummary('Appointment');
  $event->setLocation('Somewhere');
  $start = new EventDateTime();
  date_default_timezone_set('UTC');
  $t = time();
  $sdt = date('Y-m-d\T15:00:00.000\Z');
  $edt = date('Y-m-d\T15:30:00.000\Z');
  $start->setDateTime($sdt);
  $event->setStart($start);
  $end = new EventDateTime();
  $end->setDateTime($edt);
  $event->setEnd($end);
  $createdEvent = $cal->events->insert('benallfree.com_04a84gs3p2a0bdh7ru36e6ahao@group.calendar.google.com', $event);
  var_dump($createdEvent);
  echo $createdEvent['id'];  

$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}