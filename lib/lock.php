<?

register_shutdown_function('unlock_all');

function lock($lock_key, $should_block=true)
{
  $has_lock=false;
  while(!$has_lock)
  {
    $res = query_assoc("select get_lock('$lock_key',1) lck");
    $has_lock = $res[0]['lck'];
    if(!$should_block) break;
  }
  if($has_lock)
  {
    global $__wicked;
    $__wicked['locks'][$lock_key] = true;
  }
  return $has_lock;
}

function unlock($lock_key)
{
  query_assoc("select release_lock('$lock_key')");
  global $__wicked;
  unset($__wicked['locks'][$lock_key]);
}

function unlock_all()
{
  global $__wicked;
  foreach($__wicked['locks'] as $lock_key=>$info)
  {
    unlock($lock_key);
  }
}