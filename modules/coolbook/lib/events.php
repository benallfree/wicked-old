<?

function coolbook_header()
{
  global $__wicked;
  $config = $__wicked['modules']['coolbook'];
  extract($__wicked['globals'], EXTR_REFS);
  require($config['fpath'].'/templates/header.php');
}

function coolbook_footer()
{
  global $__wicked;
  $config = $__wicked['modules']['coolbook'];
  extract($__wicked['globals'], EXTR_REFS);
  require($config['fpath'].'/templates/footer.php');
}