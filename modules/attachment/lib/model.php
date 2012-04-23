<?

function attachment_attachment_before_delete($attachment)
{
  if(file_exists($attachment->fpath))
  {
  	unlink($attachment->fpath);
  }
}

function attachment_attachment_after_load($attachment)
{
  global $__wicked;
  $config = $__wicked['modules']['attachment']['config'];
  $attachment->vpath = "/data/attachments/$attachment->local_file_name";
  $attachment->fpath = $config['data_fpath'] . "/$attachment->local_file_name";
}

