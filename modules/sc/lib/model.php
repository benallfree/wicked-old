<?


function sc_secure_callback_before_validate($sc)
{
  if (!$sc->token)
  {
    $sc->token = md5(json_encode($sc->data) . uniqid() . microtime() . session_id());
  }
}

function sc_secure_callback_serialize($sc)
{
  $sc->data = json_encode($sc->data);
}

function sc_secure_callback_unserialize($sc)
{
  $sc->data = json_decode($sc->data,true);
}
