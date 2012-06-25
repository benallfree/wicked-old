<?

function create_secure_callback_url($func_name)
{
  $args = func_get_args();
  array_shift($args);
  $args = array(
    'name'=>$func_name,
    'args'=>$args,
  );
  $callback = SecureCallback::create( array(
    'attributes'=>array(
      'data'=>$args
    )
  ));
  $url = "/sc?a={$callback->token}";
  return $url;
}

function callback_data($token)
{
  $cb = SecureCallback::find_by_token($token);
  if ($cb) return $cb->args;
  return null;
}

function expire_callback($token)
{
  $cb = SecureCallback::find_by_token($token);
  if($cb) $cb->expire();
}