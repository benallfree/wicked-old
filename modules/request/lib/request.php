<?

function p($name, $val='')
{
  $elems = preg_split("/[\[\]]/", $name);
  $name = '';
  foreach($elems as $e)
  {
    if(!$e) continue;
    $name.= "['$e']";
  }
  global $__wicked;
  $p = $__wicked['modules']['request']['request'];
  if (eval("return isset(\$p['params']$name);")) return eval("return \$p['params']$name;");
  return $val;
}

function q($s, $default='')
{
  if (!array_key_exists($s,$_REQUEST)) return $default;
  return $_REQUEST[$s];
}