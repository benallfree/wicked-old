<?

function meta_get($o, $name, $default=null)
{
  if(!$o->id) return $default;
  $m = _meta_get($o, $name, $default);
  return $m->value;
}

function _meta_get_type($o,$name)
{
  global $__wicked;
  $cache = &$__wicked['modules']['meta']['type_cache'];
  $class = get_class($o);
  if(!isset($cache[$class])) $cache[$class] = array();
  if(isset($cache[$class][$name])) return $cache[$class][$name];
  
  $type = MetaType::find_or_create_by(array(
    'conditions'=>array('object_type = ? and name = ?', $class, $name),
    'attributes'=>array(
      'object_type'=>$class,
      'data_type'=>'string',
      'name'=>$name,
      'autoload'=>false,
    ),
  ));
  $cache[$class][$name] = $type;

  if($type->autoload)
  {
    $all_metas = $type->meta_values;
    $value_cache = &$__wicked['modules']['meta']['value_cache'];
    $value_cache[$type->id] = array();
    foreach($all_metas as $m)
    {
      $value_cache[$type->id][$o->id] = $m;
    }
  }  
  return $type;
}

function _meta_get_value($o, $type, $default)
{
  global $__wicked;
  $cache = &$__wicked['modules']['meta']['value_cache'];
  $class = get_class($o);
  if(!isset($cache[$type->id])) $cache[$type->id] = array();
  if(isset($cache[$type->id][$o->id])) return $cache[$type->id][$o->id];
  
  $m = MetaValue::find_or_create_by( array(
    'conditions'=>array('type_id = ? and object_id = ?', $type->id, $o->id),
    'attributes'=>array(
      'type_id'=>$type->id,
      'object_id'=>$o->id,
      'value'=>$default,
    ),
  ));
  $cache[$type->id][$o->id] = $m;
  return $m;
}

function _meta_get($o, $name, $default)
{
  $type = _meta_get_type($o,$name);
  $value = _meta_get_value($o, $type, $default);
  return $value;
}

function meta_set($o, $name, $v)
{
  if(!$o->id) return $v;
  $m = _meta_get($o, $name, $v);
  $m->value = $v;
  $m->save();
  return $m->value;
}
