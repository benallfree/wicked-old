<?


function flash_next($msg)
{
  $_SESSION['flash_next'][] = $msg;
}

function flash($msg)
{
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