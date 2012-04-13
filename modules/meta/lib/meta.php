<?

function meta_get($o, $name, $default=null)
{
  if(!$o->id) return $default;
  $m = _meta_get($o, $name, $default);
  return $m->value;
}

function _meta_get($o, $name, $default)
{
  global $__wicked;
  $cache = &$__wicked['modules']['meta']['config']['object_cache'];
  $class = get_class($o);
  if(!isset($cache[$class])) $cache[$class] = array();
  if(isset($cache[$class][$o->id][$name])) return $cache[$class][$o->id][$name];
  $all_metas = Meta::find_all(array(
    'conditions'=>array('class_name = ? and object_id = ?', $class, $o->id),
  ));
  foreach($all_metas as $m)
  {
    $cache[$class][$o->id][$m->name] = $m;
  }
  if(isset($cache[$class][$o->id][$name])) return $cache[$class][$o->id][$name];
  
  $m = Meta::create( array(
    'attributes'=>array(
      'class_name'=>get_class($o),
      'object_id'=>$o->id,
      'name'=>$name,
      'value'=>$default,
    ),
  ));
  $cache[$class][$o->id][$m->name] = $m;
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
