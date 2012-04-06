<?

function preload_module($module_fpath)
{
  global $__wicked;
  
  $m_name = basename($module_fpath);
  
  if(isset($__wicked['modules'][$m_name])) return;
 
  if(!file_exists($module_fpath)) wicked_error("Module $m_name not found. Did you misspell it?");

  define(strtoupper($m_name).'_FPATH', $module_fpath);
  $cache_fpath = CACHE_FPATH."/{$m_name}";
  define(strtoupper($m_name).'_CACHE_FPATH', $cache_fpath);

  ensure_writable_folder($cache_fpath);
  
  // Load the default config file
  $default_config = array(
    'requires'=>array(),
    'default_route'=>'index',
    'module_fpath'=>$module_fpath,
    'always_load'=>false,
    'cache_fpath'=>$cache_fpath,
  );
  $config = array();
  $config_fname = $module_fpath."/config.php";
  if(file_exists($config_fname))
  {
    require($config_fname);
  }
  $config = array_merge($default_config, $config);

  // Load any custom user config files
  $config_fname = ROOT_FPATH."/app/config/{$m_name}.php";
  if(file_exists($config_fname))
  {
    $current_config = $config;
    require($config_fname);
    $config = array_merge($current_config, $config);
  }
  $__wicked['modules'][$m_name] = array('config'=>$config, 'is_loaded'=>false);
  
  // Process the preloader
  $preload_fpath = $module_fpath."/preload.php";
  if(file_exists($preload_fpath))
  {
    require_once($preload_fpath);
  }
}

function load_module($m_name)
{
  global $__wicked;
  
  if(!isset($__wicked['modules'][$m_name]['config'])) return null;

  $config = &$__wicked['modules'][$m_name]['config'];
  
  if($__wicked['modules'][$m_name]['is_loaded']) return $config;

  $__wicked['modules'][$m_name]['is_loaded'] = true;
  
  $module_fpath = $config['module_fpath'];
  
  // Configure any class paths
  $class_fpath = $module_fpath."/classes";
  if(file_exists($class_fpath))
  {
    $__wicked['autoload_fpaths'][] = $class_fpath;
  }

  // Load required modules
  foreach($config['requires'] as $r)
  {
    load_module($r);
  }
  
  // Load this module's libraries
  foreach(wicked_glob($module_fpath."/lib/*.php") as $lib_fname)
  {
    require_once($lib_fname);
  }
  
  // Load the bootstrap
  $bootstrap_fname = $module_fpath."/load.php";
  if(file_exists($bootstrap_fname))
  {
    require($bootstrap_fname);
  }

  // Perform codegen
  $bootstrap_fname = $module_fpath."/codegen.php";
  if(file_exists($bootstrap_fname))
  {
    lock($config['cache_fpath']);
    require($bootstrap_fname);
    unlock($config['cache_fpath']);
  }
  
  return $config;
}
