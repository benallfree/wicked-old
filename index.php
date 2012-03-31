<?
date_default_timezone_set('UTC');
error_reporting(E_STRICT | E_ALL);

if(!file_exists('config.php')) die("Please copy config.template.php to config.php");
require_once('config.php');

// Load core libraries
foreach(glob(dirname(__FILE__)."/lib/*.php") as $fname)
{
  require_once($fname);
}


define('ROOT_FPATH', dirname(__FILE__));
define('ROOT_VPATH', dirname($_SERVER['SCRIPT_NAME']));

$__wicked_defaults = array(
  'autoload_fpaths'=>array(),
  'route_handlers'=>array(
    'php'=>array('', 'handle_php_route'),
  ),
  'use_theme'=>true,
  'use_ssl'=>true,
);

if (get_magic_quotes_gpc())
{
  $_POST = array_map('stripslashes_deep', $_POST);
  $_GET = array_map('stripslashes_deep', $_GET);
  $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
  $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

$path = substr($_SERVER['REQUEST_URI'], strlen('/'));
$parts = explode('?', $path);
$full_request_path = trim($_SERVER['REQUEST_URI'],"/");
$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$current_url = "{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$request_path = vpath("/{$parts[0]}");
$params = array_merge($_GET, $_POST);

$host = $_SERVER['HTTP_HOST'];
$parts = explode('.', $host);

$domain = join('.', array_slice($parts, -2, 2));

$subdomain = join('.', array_slice($parts, 0, count($parts)-2));

$querystring = $_SERVER['QUERY_STRING'];

if (strpos($subdomain, '_')) trigger_error("Subdomains with _ are not supported. They break sessions in IE7/8, possibly others.", E_USER_ERROR);

$fields = array('name', 'type', 'tmp_name', 'error', 'size');
foreach($_FILES as $k=>$v) {
  foreach($fields as $field) {
    if (count($v['name'])==0) break;
    merge_bottom($params[$k], $v[$field], $field);
  }
}

$__wicked_defaults['request'] = array(
  'domain'=>$domain,
  'subdomain'=>$subdomain,
  'host'=>$host,
  'querystring'=>$querystring,
  'request_path'=>$request_path,
  'params'=>$params,
  'current_url'=>$current_url,
  'protocol'=>$protocol,
);


$__wicked = array_merge($__wicked_defaults, $config);

function __autoload($class)
{
  global $__wicked;
  foreach($__wicked['autoload_fpaths'] as $fpath)
  {
    $fname = $fpath."/$class.class.php";
    if(!file_exists($fname)) continue;
    require_once($fname);
    return true;
  }
  return false;
}
spl_autoload_register('__autoload');



session_start();

// Preload modules
foreach(array('modules', 'app/modules') as $loc)
{
  foreach(wicked_glob(dirname(__FILE__)."/{$loc}/*", GLOB_ONLYDIR) as $module_fpath)
  {
    preload_module($module_fpath);
  }
}

// Load required modules
foreach($__wicked['modules'] as $module_name=>$module_info)
{
  if(!$module_info['config']['always_load']) continue;
  load_module($module_name);
}

// Parse route
$parts = parse_url($_SERVER['REQUEST_URI']);
$route = trim($parts['path'], '/');
if($route=='') $route = $__wicked['default_module'];
$parts = explode('/',$route);

if(count($parts)==0) $parts = array($config['default_module']);
$this_module_name = array_shift($parts);
$config = load_module($this_module_name);

if(count($parts)==0)
{
  $parts[] = $config['default_route'];
}

$route_path = join('/', $parts);

foreach($__wicked['route_handlers'] as $ext=>$handler_info)
{
  foreach(array('','/'.$config['default_route'], '/index') as $extra)
  {
    $try_route_path = $route_path . $extra;
    $body_template = $config['module_fpath']."/routes/{$try_route_path}.{$ext}";
    if(file_exists($body_template))
    {
      list($module_name, $handler_name) = $handler_info;
      if($module_name) 
      {
        load_module($module_name);
      }
      $body_template = call_user_func($handler_name, $body_template);
      break 2;
    }

  }
}

$root_fpath = dirname(__FILE__);
$this_module_fpath = dirname($config['module_fpath']."/routes/{$try_route_path}");
$this_module_vpath = "/$this_module_name/".dirname($try_route_path);
$this_module_resource_vpath = substr($this_module_fpath, strlen($root_fpath));

$params = $__wicked['request']['params'];

$theme_config = load_module($__wicked['theme']);
ob_start();
if(file_exists($body_template))
{
  require($body_template);
} else {
  $config = $theme_config;
  require("modules/{$__wicked['theme']}/404.php");
}
$s = ob_get_clean();

$config = $theme_config;
if($__wicked['use_theme']) require("modules/{$__wicked['theme']}/header.php");
echo $s;
if($__wicked['use_theme']) require("modules/{$__wicked['theme']}/footer.php");
