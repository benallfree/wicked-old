<?

function eval_php($__path__, $__data__=array(), $__capture__=false)
{
  if($__data__) extract($__data__, EXTR_REFS);
  if($__capture__) ob_start();
  require($__path__);
  if($__capture__)
  {
    $__s__ = ob_get_contents();
    ob_end_clean();
    return $__s__;
  }
}

function php_to_php($fpath)
{
  return $fpath;
}