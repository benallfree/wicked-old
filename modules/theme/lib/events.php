<?

function theme_kernel_run()
{
  ob_start();
  do_action('header');
  do_action('content');
  do_action('footer');
  echo ob_get_clean();
}