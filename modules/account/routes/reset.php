<?php 

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

$err = array();
$msg = array();

$user = User::find( array(
  'conditions'=>array('md5_id = ? and activation_code = ?', p('user'), p('activ_code')),
));

if ( !$user ) { 
	flash_next("Sorry no such account exists or reset code is invalid.");
	redirect_to('/');
} else {
  login($user, '/account');
}

die;