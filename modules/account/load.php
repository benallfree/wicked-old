<?

global $current_user;
$current_user = new User();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'])
{
  $current_user = User::find_by_id($_SESSION['user_id']);
}

