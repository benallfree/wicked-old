<?

$tok = p('a');
$sc = SecureCallback::find_by_token($tok, array(
  'conditions'=>array('used_at is null and (expires_at is null or expires_at > utc_timestamp())')
));
if(!$sc)
{
  redirect_to('/sc/notfound', array('a'=>$tok));
}
$sc->expire();
call_user_func_array($sc->data['name'], $sc->data['args']);