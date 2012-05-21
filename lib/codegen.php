<?

function codegen()
{
  global $__wicked;
  
  $php = array('<?',);
  
  $__wicked_defaults = array(
    'fpath'=>realpath(dirname(__FILE__)."/.."),
    'vpath'=>dirname($_SERVER['SCRIPT_NAME']),
    'autoload_fpaths'=>array(),
    'use_theme'=>true,
    'use_ssl'=>true,
    'locks'=>array(),
    'globals'=>array(
      'root_fpath'=>dirname(__FILE__),
    ),
    'codegen'=>array(),
    'events'=>array(),
  );
  
  define('ROOT_FPATH', $__wicked_defaults['fpath']);
  define('ROOT_VPATH', $__wicked_defaults['vpath']);
  define('CACHE_FPATH', $__wicked['cache_fpath']);
    
  $__wicked = array_merge($__wicked_defaults, $__wicked);
  
  // Preload modules
  $modules = array();
  foreach(array('modules', 'app/modules') as $loc)
  {
    foreach(wicked_glob($__wicked['fpath']."/{$loc}/*", GLOB_ONLYDIR) as $module_fpath)
    {
      $m_name = basename($module_fpath);
      $modules[$m_name] = $module_fpath;
    }
  }
  $__wicked['modules'] = array();
  $__wicked['module_fpaths'] = $modules;
  foreach($modules as $module_fpath)
  {
    preload_module($module_fpath);
    $class_fpath = $module_fpath."/classes";
    if(file_exists($class_fpath))
    {
      $__wicked['autoload_fpaths'][] = $class_fpath;
    }
  }

  // Perform codegen
  foreach($__wicked['modules'] as $module_name=>$module_info)
  {
    load_module($module_name);
  }
  foreach($__wicked['modules'] as $module_name=>$module_info)
  {
    $__wicked['modules'][$module_name]['is_loaded'] = false;
  }

  $codegen = join("\n;\n", $__wicked['codegen']);
  unset($__wicked['codegen']);
  unset($__wicked['events']);
  $s = s_var_export($__wicked);
  $php[] = "
    /*
    ===============
    CORE 
    ===============
    */
    \$__wicked = $s;
    define('ROOT_FPATH', \$__wicked['fpath']);
    define('ROOT_VPATH', \$__wicked['vpath']);
    define('CACHE_FPATH', \$__wicked['cache_fpath']);  
  ";
  
  foreach($__wicked['modules'] as $module_name=>$module_info)
  {
    $n = strtoupper($module_name);
    $f = $module_info['fpath'];
    $c = $module_info['cache_fpath'];
    $php[] = "
      define('{$n}_FPATH', '$f');
      define('{$n}_CACHE_FPATH', '$c');
    ";
  
  }
  $php[] = $codegen;
  
  return $php;
}