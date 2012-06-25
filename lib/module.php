<?


function load_config($module_fpath)
{
  $m_name = basename($module_fpath);
  
  $cache_fpath = CACHE_FPATH."/{$m_name}";
  ensure_writable_folder($cache_fpath);
  
  // Load the default config file
  $default_config = array(
    'name'=>$m_name,
    'requires'=>array(),
    'default_route'=>'index',
    'fpath'=>$module_fpath,
    'vpath'=>substr($module_fpath,strlen(ROOT_FPATH)),
    'always_load'=>false,
    'cache_fpath'=>$cache_fpath,
    'is_loaded'=>false,
    'observes'=>array(),
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
  
  return $config;
}


function preload_module($module_fpath)
{
  global $__wicked;
  $m_name = basename($module_fpath);
  if(isset($__wicked['modules'][$m_name])) return;
  $config = load_config($module_fpath);
  foreach($config['requires'] as $r_name)
  {
    if(!isset($__wicked['module_fpaths'][$r_name])) wicked_error("$m_name requires $r_name, but $r_name does not exist.");
    $r_fpath = $__wicked['module_fpaths'][$r_name];
    preload_module($r_fpath);
  }
  $__wicked['modules'][$m_name] = $config;
  
  return;
}

function load_module($m_name)
{
  global $__wicked;
 
  if(!isset($__wicked['modules'][$m_name])) wicked_error("Attempted to load undefined module $m_name.");

  $config = &$__wicked['modules'][$m_name];
  
  if($__wicked['modules'][$m_name]['is_loaded']) return $config;

  $__wicked['modules'][$m_name]['is_loaded'] = true;
  
  $module_fpath = $config['fpath'];
  
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
  if(IS_CLI)
  {
    $codegen_fname = $module_fpath."/codegen.php";
    if(!file_exists($codegen_fname)) return $config;
    require($codegen_fname);
  }
  
  return $config;
}
