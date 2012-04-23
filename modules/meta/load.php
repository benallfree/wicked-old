<?

observe('meta_value_serialize', 'meta_serialize');
observe('meta_value_unserialize', 'meta_unserialize');

function meta_serialize($m)
{
  $m->value = json_encode($m->value);
}

function meta_unserialize($m)
{
  $m->value = json_decode($m->value, true);
}