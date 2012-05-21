<?

$ar_config = $__wicked['modules']['activerecord'];

foreach($ar_config['models'] as $class)
{
  if($class=='Meta') continue;
  $n = singularize(tableize($class));
  $__wicked['codegen'][] = "
    function {$n}_meta(\$o, \$name, \$default=null)
    {
      return meta_get(\$o, \$name, \$default);
    }
    function {$n}_set_meta(\$o, \$name, \$val)
    {
      return meta_set(\$o, \$name, \$val);
    }
  ";
}