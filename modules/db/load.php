<?

foreach($config['databases'] as $name=>$settings)
{
  db_add($name, $settings);
}
db_select('default');
