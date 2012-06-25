<?

function simple_routing_parse_request($request)
{
  global $__wicked;
  $path = trim($request['path'], '/');
  if(!$path)
  {
    $path = $__wicked['modules']['simple_routing']['default_route'];
  }
  $request['path'] = $path;
  return $request;
}

function simple_routing_content()
{
  global $__wicked;
  $request = $__wicked['modules']['request']['request'];
  
  $path = trim($request['path'], '/');
  $pieces = explode('/',$path);

  $module_name = array_shift($pieces);
  $config = $__wicked['modules'][$module_name];

  $route_fpath = $config['fpath']."/routes/".join("/",$pieces);
  
  $events = wicked_glob($route_fpath.".*");
  if(count($events)==0)
  {
    $route_fpath .= "/index";
    $events = wicked_glob($route_fpath.".*");
  }

  if(count($events)>0)
  {
    load_module($module_name);
    $args = array();
    foreach($__wicked['globals'] as $k=>&$v)
    {
      $args[$k] = &$v;
    }
    $args['config'] = &$config; 
    $args['this_module_name'] = $module_name;
    $args['this_module_fpath'] = $config['fpath'];
    $args['this_module_vpath'] = $config['vpath'];
    $args['this_module_resource_vpath'] = substr($config['fpath'], strlen($__wicked['fpath']));
    $args['this_event_name'] = 'content';
  
    foreach($events as $fname)
    {
      $parts = pathinfo($fname);
      $filter_name = "{$parts['extension']}_to_php";
      $files[] = do_filter($filter_name, $fname);
    }
    
    foreach($files as $f)
    {
      eval_php($f, $args);
    }
  }
}
