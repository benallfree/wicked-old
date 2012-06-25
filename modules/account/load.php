<?

observe('find_login', 'account_find_login');
function account_find_login($u, $login)
{
  $user = User::find( array(
    'conditions'=>array('email = ? or login = ?', $login, $login),
  ));
  return $user;

}


global $current_user;
$current_user = new User();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'])
{
  $current_user = User::find_by_id($_SESSION['user_id']);
}

