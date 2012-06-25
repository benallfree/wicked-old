<?

function eval_php($__path__, $__data__=array(), $__capture__ = false)
{
  global $__wicked;
  if($__data__) extract($__data__, EXTR_REFS);
  if($__capture__) ob_start();
  $__res = require($__path__);
  if($__capture__)
  {
  $__capture__ = ob_get_contents();
    ob_end_clean();
    return $__capture__;
  }
}
