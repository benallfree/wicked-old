<?

function globals_kernel_run()
{
  global $__wicked;
  $__wicked['globals'] = do_filter('add_globals', array());
}
