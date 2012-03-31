<?


function error_next($msg)
{
  $_SESSION['error_next'][] = $msg;
}

function error($msg)
{
  $_SESSION['error'][] = $msg;
}

function has_error()
{
  return count($_SESSION['error'])>0;
}

function get_error()
{
  $error = $_SESSION['error'];
  $_SESSION['error'] = array();
  return $error;
}