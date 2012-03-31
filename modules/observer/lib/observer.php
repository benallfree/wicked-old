<?

function apply_filters($filter_name, $arg)
{
  return $arg;
}

function action($event_name)
{
  global $__wicked;
  if(!isset($__wicked['modules']['observer']['events'])) $__wicked['modules']['observer']['events'] = array();
  $events = $__wicked['modules']['observer']['events'];
  if(!isset($events[$event_name])) return;
  $event = $events[$event_name];
  $args = func_get_args();
  array_shift($args);
  foreach($event as $event_info)
  {
    call_user_func_array($event_info[0], $args);
  }
}

function observe($event_name, $callback, $weight=10)
{
  global $__wicked;
  $events = &$__wicked['modules']['observer']['events'];
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