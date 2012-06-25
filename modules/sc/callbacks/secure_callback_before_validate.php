<?

if (!$sc->token)
{
  $sc->token = md5(json_encode($sc->data) . uniqid() . microtime() . session_id());
}
