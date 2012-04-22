<?

define ("ADMIN_LEVEL", 5);
define ("USER_LEVEL", 1);
define ("GUEST_LEVEL", 0);
define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password


$config = array(
  'requires'=>array('swiftmail', 'eval_php', 'db', 'activerecord', 'observer', 'meta', 'template'),
  'after_activation_url'=>'/',
  'after_login_url'=>'/',
  'recaptcha'=>array(
    'public_key'=>'',
    'private_key'=>'',
  ),
  
);
