<?

function __autoload($class)
{
  global $__wicked;
  foreach($__wicked['autoload_fpaths'] as $fpath)
  {
    $fname = $fpath."/$class.class.php";
    if(!file_exists($fname)) continue;
    require_once($fname);
    return true;
  }
  return false;
}
spl_autoload_register('__autoload');

