<?

$config = array(
  'requires'=>array('eval_php', 'inflection', 'db', 'collections', 'exec','string',),
);
$config['tables'] = array(
  'include'=>array(),
  'exclude'=>array(),
);

$defaults = array(
  'requires'=>array('db', 'exec', 'inflection','collections','observer', 'string',),
  'prefix'=>'',
  'tables'=>array(),
  'ft_min_word_len'=>4,
  'type_mappings'=>array(),
  'conventions'=>array(),
  'always_generate'=>false,
  'class_prefix'=>'',
);
foreach($defaults as $k=>$v)
{
  if(!isset($config[$k])) $config[$k] = $v;
}

$mappings = array(
  'int'=>'integer',
  'tinyint'=>'check',
  'datetime'=>'date',
  'longtext'=>'textarea',
  'text'=>'text',
  'varchar'=>'text',
  'char'=>'text',
  'decimal'=>'float',
  'double'=>'float',
  'bigint'=>'integer',
  'blob'=>'blob',
  'float'=>'float',
  'smallint'=>'integer',
  'enum'=>'text',
  'timestamp'=>'date',
  'tinytext'=>'text',
  'date'=>'date',
);

foreach($mappings as $k=>$v)
{
  if(!isset($config['type_mappings'][$k])) $config['type_mappings'][$k] = $v;
}

$conventions = array(
  'decimal'=>array(
    'price'=>'currency',
    'budget'=>'currency',
  ),
  'varchar'=>array(
    'status'=>'title',
    'email'=>'email_address',
    'zip'=>'zip_code',
    'phone'=>'phone_number',
  ),
);
foreach($conventions as $k=>$v)
{
  if(!isset($config['conventions'][$k])) $config['conventions'][$k] = $v;
}




