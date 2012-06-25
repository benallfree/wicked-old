<?

function attachment_attachment_before_delete($a)
{
  if(file_exists($a->fpath))
  {
  	unlink($a->fpath);
  }
}

function attachment_attachment_after_load($a)
{
  global $__wicked;
  $config = $__wicked['modules']['attachment'];
  $a->vpath = "/data/attachments/$a->local_file_name";
  $a->fpath = $config['data_fpath'] . "/$a->local_file_name";
}
