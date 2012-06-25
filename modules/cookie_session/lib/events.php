<?

function cookie_session_kernel_start()
{
  global $__wicked;
  $config = $__wicked['modules']['cookie_session'];
  
  CookieCache::init($config);
}