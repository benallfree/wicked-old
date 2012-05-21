<?

$__wicked['events'] = array();
function do_action($event_name)
{
  global $__wicked;
  $events = $__wicked['events'];
  $args = func_get_args();
  array_shift($args);
  if(count($args)==0) $args = array(null);
  if(!isset($events[$event_name])) return $args[0];
  $event = $events[$event_name];
  foreach($event as $event_info)
  {
    load_module($event_info[1]);
    if(!function_exists($event_info[0]))
    {
      wicked_error("Callback {$event_info[0]} does not exist (called from $event_name).");
    }
    call_user_func_array($event_info[0], $args);
  }
}

function do_filter($event_name)
{
  global $__wicked;
  $events = $__wicked['events'];
  $args = func_get_args();
  array_shift($args);
  if(count($args)==0) $args = array(null);
  if(!isset($events[$event_name])) return $args[0];
  $event = $events[$event_name];
  foreach($event as $event_info)
  {
    load_module($event_info[1]);
    if(!function_exists($event_info[0]))
    {
      wicked_error("Callback {$event_info[0]} does not exist (called from $event_name).");
    }
    $res = call_user_func_array($event_info[0], $args);
    if($args[0]!=null && $res==null)
    {
      wicked_error("Filter {$event_info[0]} did not return a value.");
    }
    $args[0] = $res;
  }
  return $args[0];
}


function observe($event_name, $module_name, $callback, $weight=10)
{
  global $__wicked;
  $events = &$__wicked['events'];
  if(!isset($events[$event_name])) $events[$event_name] = array();
  $event = &$events[$event_name];
  $event[] = array($callback, $module_name, $weight);
  usort($event, 'observer_event_sort');
}

function remove_observer($event_name, $callback)
{
  global $__wicked;
  $events = &$__wicked['events'];
  if(!isset($events[$event_name])) $events[$event_name] = array();
  $event = &$events[$event_name];
  for($i=0;$i<count($event);$i++)
  {
    if($event[$i][0]!=$callback) continue;
    unset($event[$i]);
    break;
  }
  usort($event, 'observer_event_sort');
}

function observer_event_sort($a,$b)
{
  if($a[2]<$b[2]) return -1;
  if($a[2]>$b[2]) return 1;
  return 0;
}