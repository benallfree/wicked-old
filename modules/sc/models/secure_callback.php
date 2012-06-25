<?

function secure_callback_expire($sc)
{
  if(!$sc->expires_at) return;
  $sc->used_at = time();
  $sc->save();
}