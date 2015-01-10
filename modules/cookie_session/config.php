<?

$config = array(
  'name'=>'data',
  'secret_key'=>ROOT_FPATH,
  'digest_method'=>'sha256',
  'ttl'=>30*60,
  'domain'=>'',
  'path'=>'/',
  'observes'=>array(
    'kernel_start',
  ),
);
