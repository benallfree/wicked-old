<?

observe('meta_serialize', 'meta_serialize');
observe('meta_unserialize', 'meta_unserialize');

function meta_serialize($m)
{
  $v = json_encode(deep_utf8_encode($m->value));
  if($v===false) wicked_error(json_last_error());
  $m->value = $v;
}

function meta_unserialize($m)
{
  $m->value = json_decode($m->value, true);
}
