<?

function user_get_calendars($u)
{
  $data = $u->gcal_cal_service->calendarList->listCalendarList();
  $cals = array();
  foreach($data["items"] as $cal)
  {
    if($cal['accessRole']!='owner') continue;
    $cal['name'] = $cal['summary'];
    $cals[] = $cal;
  }
  return $cals;
}


function user_get_gcal_client($u)
{
  $client = new apiClient();
  $client->setApplicationName("Google Calendar PHP Starter Application");
  
  $config = $__wicked['modules']['gcal']['config'];
  
  // Visit https://code.google.com/apis/console?api=calendar to generate your
  // client id, client secret, and to register your redirect uri.
  $client->setClientId($config['client_id']);
  $client->setClientSecret($config['client_secret']);
  $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . "/gcal/authorize");
  $client->setDeveloperKey($config['developer_key']);
  if($u->meta('gcal_token'))
  {
    $client->setAccessToken($u->meta('gcal_token'));
  }
  return $client;
}

function user_get_gcal_cal_service($u)
{
  $service = new apiCalendarService($u->gcal_client);
  return $service;
}
