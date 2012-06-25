<?

$config =array(
  'data_fpath'=>ROOT_FPATH."/data/attachments",
  'requires'=>array('activerecord'),
  'observes'=>array(
    'attachment_after_load',
    'attachment_before_delete',
  ),
);

