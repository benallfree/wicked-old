<?

function request_kernel_start()
{
  global $__wicked;
  
  $path = substr($_SERVER['REQUEST_URI'], strlen('/'));
  $parts = explode('?', $path);
  $full_request_path = trim($_SERVER['REQUEST_URI'],"/");
  $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
  $current_url = "{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  $request_path = vpath("/{$parts[0]}");
  $params = array_merge($_GET, $_POST);
  
  // Fix file struct
  $fields = array('name', 'type', 'tmp_name', 'error', 'size');
  foreach($_FILES as $k=>$v) {
    foreach($fields as $field)
    {
      if (count($v['name'])==0) break;
      merge_bottom($params[$k], $v[$field], $field);
    }
  }
  
  $host = $_SERVER['HTTP_HOST'];
  $parts = explode('.', $host);
  
  $domain = join('.', array_slice($parts, -2, 2));
  
  $subdomain = join('.', array_slice($parts, 0, count($parts)-2));
  
  $querystring = $_SERVER['QUERY_STRING'];
  
  if (strpos($subdomain, '_')) trigger_error("Subdomains with _ are not supported. They break sessions in IE7/8, possibly others.", E_USER_ERROR);
  
  $request = do_filter('parse_request', array(
    'domain'=>$domain,
    'subdomain'=>$subdomain,
    'host'=>$host,
    'querystring'=>$querystring,
    'path'=>$request_path,
    'params'=>$params,
    'current_url'=>$current_url,
    'protocol'=>$protocol,
  ));
  $__wicked['modules']['request']['request'] = $request;
}

function request_add_globals($g)
{
  global $__wicked;
  $request = &$__wicked['modules']['request']['request'];
  $g['request'] = &$request;
  $g['params'] = &$request['params'];
  return $g;
}