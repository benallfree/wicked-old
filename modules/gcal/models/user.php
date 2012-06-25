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


function user_get_gcal_client__d($u)
{
  global $__wicked;
  $config = $__wicked['modules']['gcal'];
  
  $client = new apiClient();
  $client->setApplicationName($__wicked['app_title']);
  
  $client->setClientId($config['client_id']);
  $client->setClientSecret($config['client_secret']);
  $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . "/gcal/authorize");
  $client->setDeveloperKey($config['developer_key']);
  if($u->meta('gcal_token'))
  {
    $client->setAccessToken($u->meta('gcal_token'));
  }
  $cal = new apiCalendarService($client); // required to create auth request
  
  return $client;
}

function user_get_gcal_cal_service__d($u)
{
  $service = new apiCalendarService($u->gcal_client);
  return $service;
}

function user_gcal_delete_all($u,$cal_id)
{
  $nextPageToken = null;
  do
  {
    $args = array();
    if($nextPageToken)
    {
      $args['pageToken'] = $events['nextPageToken'];
    }    
    $events = $u->gcal_cal_service->events->listEvents($cal_id, $args);
    if(isset($events['items']))
    {
      foreach($events['items'] as $i)
      {
        if($i['status']=='cancelled') continue;
        $res = $u->gcal_cal_service->events->delete($cal_id, $i['id']);
      }
    }
    $nextPageToken = isset($events['nextPageToken']) ? $events['nextPageToken'] : null;
  } while ($nextPageToken);
  $u->set_meta('credit_slots', array());  
}