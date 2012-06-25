<?

$default_sizes = array(
  'micro'=>25,
  'icon'=>60,
  'tiny'=>100,
  'small'=>160,
  'smallish'=>250,
  'sample'=>275,
  'medium'=>450,
  'large'=>900
);

$default_settings = array(
  'rad'=>false, // 7
  'bg'=>false, // '#fff'
  'ds'=>false, // '#000',
  'zc'=>false, // true
);


if(!isset($config['sizes'])) $config['sizes'] = array();
if(!isset($config['settings'])) $config['settings'] = array();

$config['sizes'] = array_merge($default_sizes, $config['sizes']);
$config['settings'] = array_merge($default_settings, $config['settings']);

