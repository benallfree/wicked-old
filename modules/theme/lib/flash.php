<?


function flash_next()
{
  $args = func_get_args();
  $msg = call_user_func_array('flash_interpolate', $args);
  $_SESSION['flash_next'][] = $msg;
}

function flash()
{
  $args = func_get_args();
  $msg = call_user_func_array('flash_interpolate', $args);
  $_SESSION['flash'][] = $msg;
}

function has_flash()
{
  return count($_SESSION['flash'])>0;
}

function get_flash()
{
  $flash = $_SESSION['flash'];
  $_SESSION['flash'] = array();
  return $flash;
}

function flash_interpolate()
{
  $msg = func_get_arg(0);
  for($i=1;$i<func_num_args();$i++)
  {
    $v = func_get_arg($i);
    $msg = preg_replace("/\?/", $v, $msg);
  }
  return $msg;
}