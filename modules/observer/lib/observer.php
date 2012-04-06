<?

function event($event_name)
{
  global $__wicked;
  $events = $__wicked['modules']['observer']['config']['events'];
  $args = func_get_args();
  array_shift($args);
  if(count($args)==0) $args = array(null);
  if(!isset($events[$event_name])) return $args[0];
  $event = $events[$event_name];
  foreach($event as $event_info)
  {
    $args[0] = call_user_func_array($event_info[0], $args);
  }
  return $args[0];
}

function observe($event_name, $callback, $weight=10)
{
  global $__wicked;
  $events = &$__wicked['modules']['observer']['config']['events'];
  if(!isset($events[$event_name])) $events[$event_name] = array();
  $event = &$events[$event_name];
  $event[] = array($callback, $weight);
  usort($event, 'observer_event_sort');
}

function observer_event_sort($a,$b)
{
  if($a[1]>$b[1]) return -1;
  if($a[1]<$b[1]) return 1;
  return 0;
}