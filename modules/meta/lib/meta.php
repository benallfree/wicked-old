<?

function meta_get($o, $name, $default=null)
{
  if(!$o->id) return $default;
  $m = _meta_get($o, $name, $default);
  return $m->value;
}

function _meta_get($o, $name, $default)
{
  $m = Meta::find_or_create_by( array(
    'conditions'=>array('class_name = ? and object_id = ? and name = ?', get_class($o), $o->id, $name),
    'attributes'=>array(
      'class_name'=>get_class($o),
      'object_id'=>$o->id,
      'name'=>$name,
      'value'=>$default,
    ),
  ));
  
  return $m;
}

function meta_set($o, $name, $v)
{
  if(!$o->id) return $v;
  $m = _meta_get($o, $name, $v);
  $m->value = $v;
  $m->save();
  return $m->value;
}
