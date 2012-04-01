<?

function do_filter($filter_name)
{
  global $__wicked;
  $events = $__wicked['modules']['observer']['config']['events']['filter'];
  $args = func_get_args();
  array_shift($args);
  if(!isset($events[$filter_name])) return $args[0];
  $event = $events[$filter_name];
  foreach($event as $event_info)
  {
    $args[0] = call_user_func_array($event_info[0], $args);
  }
  return $args[0];
}

function do_action($event_name)
{
  global $__wicked;
  $events = $__wicked['modules']['observer']['config']['events']['action'];
  if(!isset($events[$event_name])) return;
  $event = $events[$event_name];
  $args = func_get_args();
  array_shift($args);
  foreach($event as $event_info)
  {
    call_user_func_array($event_info[0], $args);
  }
}

function add_action($event_name, $callback, $weight=10)
{
  observe('action', $event_name, $callback, $weight);
}

function add_filter($event_name, $callback, $weight=10)
{
  observe('filter', $event_name, $callback, $weight);
}

function observe($event_type, $event_name, $callback, $weight=10)
{
  global $__wicked;
  $events = &$__wicked['modules']['observer']['config']['events'][$event_type];
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