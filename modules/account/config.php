<?

$config = array(
  'requires'=>array('swiftmail', 'eval_php', 'db', 'activerecord','observer', 'meta'),
  'after_activation_url'=>'/',
  'after_login_url'=>'/',
  'recaptcha'=>array(
    'public_key'=>'',
    'private_key'=>'',
  ),
  
);