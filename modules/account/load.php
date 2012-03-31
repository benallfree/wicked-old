<?

global $current_user;
$current_user = new User();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'])
{
  $current_user = User::find_by_id($_SESSION['user_id']);
}

global $__wicked;
if($current_user->meta('date_format'))
{
  $__wicked['modules']['date']['config']['date_format'] = $current_user->meta('date_format');
}
if($current_user->meta('time_format'))
{
  $__wicked['modules']['date']['config']['time_format'] = $current_user->meta('time_format');
}
if($current_user->meta('time_zone'))
{
  date_default_timezone_set($current_user->meta('time_zone'));
}
