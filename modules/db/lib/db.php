<?



function db_add($handle, $dbs)
{
  global $__wicked;
  if(!isset($__wicked['modules']['db']['connections'][$handle]) && !$dbs) wicked_error("Tried to select $handle, but no database settings were defined.");
  if($dbs)
  {
    $dbh = db_connect($dbs);
    $__wicked['modules']['db']['connections'][$handle]['handle'] = $dbh;
    $__wicked['modules']['db']['connections'][$handle]['credentials'] = $dbs;
  }
}

function db_select($handle, $dbs=null, $dbh = null)
{
  global $__wicked;
  if($dbs)
  {
    if(is_array($dbs))
    {
      db_add($handle, $dbs);
    } else {
      $__wicked['modules']['db']['connections'][$handle]['handle'] = $dbs;
      $__wicked['modules']['db']['connections'][$handle]['credentials'] = array();
    }
  }
  $__wicked['modules']['db']['current'] = $__wicked['modules']['db']['connections'][$handle];
  return $__wicked['modules']['db']['current'];
}

function db_push($handle, $dbs=null)
{
  global $__wicked;
  $__wicked['modules']['db']['connection_stack'][] = $__wicked['modules']['db']['current'];
  $__wicked['modules']['db']['current'] = db_select($handle, $dbs);
  return $__wicked['modules']['db']['current'];
}

function db_pop()
{
  global $__wicked;
  if(count($__wicked['modules']['db']['connection_stack'])>0)
  {
    $__wicked['modules']['db']['current'] = array_pop($__wicked['modules']['db']['connection_stack']);
  }
}

function db_connect($database_settings)
{
  global $__wicked;
  $dbh=mysql_connect ($database_settings['host'], $database_settings['username'],$database_settings['password']);
  if (!$dbh)
  {
    wicked_error('Cannot connect to the database because: ' . mysql_error());
  }
  if (!mysql_select_db($database_settings['catalog'], $dbh))
  {
    wicked_error(mysql_error($dbh));
  }
  return $dbh;
}

function query($sql)
{
  global $__wicked;
  $args = func_get_args();
  array_shift($args);
  $s = '';
  $in_quote = false;
  $in_escape = false;
  for($i=0;$i<strlen($sql);$i++)
  {
    if(count($args)==0)
    {
      $s .= substr($sql, $i);
      break;
    }
    $c = substr($sql, $i, 1);
    if($in_escape)
    {
      $s.=$c;
      $in_escape = false;
      continue;
    }
    if($c == "'" && !$in_quote)
    {
      $in_quote = true;
      continue;
    }
    if($c == "'" && $in_quote)
    {
      $next = substr($sql, $i+1, 1);
      if($next == "'") continue;
    }
    if($c == '\\')
    {
      $in_escape = true;
      continue;
    }
    $in_quote = false;
    switch($c)
    {
      case "'":
       $in_quote = true;
       break;
      case '?':
        $s .= "'".mysql_real_escape_string(array_shift($args))."'";
        break;
      case '!':
        $s.= array_shift($args);
        break;
      case '@':
        $s .= mysql_real_escape_string(date( 'Y-m-d H:i:s e', array_shift($args)));
        break;
      default:
        $s .= $c;
    }
  }
  $sql = $s;
  
  $sql = trim($sql);
  $__wicked['modules']['db']['queries'][]=$sql;
  if ( preg_match('/^delete|^update/mi',$sql)>0 && preg_match('/\s+where\s+/mi', $sql)==0)
  {
    wicked_error("DELETE or UPDATE error. No WHERE specified", $sql);
  }
  $start = microtime(true);
  $res = mysql_query($sql, $__wicked['modules']['db']['current']['handle']);
  $end = microtime(true);
  $__wicked['modules']['db']['queries'][] = (int)(($end-$start)*1000);
  if ($res===FALSE) {
    wicked_error(mysql_error($__wicked['modules']['db']['current']['handle']), $sql);
  }
  if (gettype($res)=='resource') $__wicked['modules']['db']['queries'][] = mysql_num_rows($res); else $__wicked['modules']['db']['queries'][] = 0;
  return $res;
}

function query_assoc($sql)
{
  global $__wicked;
  $args = func_get_args();

  $res = call_user_func_array('query', $args);
  $assoc=array();
  while($rec = mysql_fetch_assoc($res))
  {
    $assoc[]=$rec;
  }
  return $assoc;
}

function query_obj($sql)
{
  global $__wicked;
  $args = func_get_args();
  $recs = call_user_func_array('query_assoc', $args);
  $res = array();
  foreach($recs as $r)
  {
    $res[] = (object)$r;
  }
  return $res;
}

function query_file($fpath)
{
  global $__wicked;
  $d = Wax::$build['database'];
  $cmd = "mysql -u {$d['username']} --password={$d['password']} -h {$d['host']} -D {$d['catalog']} < \"$fpath\"";
  wax_exec($cmd);
}

function db_table_exists($name)
{
  global $__wicked;
  $res = query_assoc("show tables");
  
  foreach(array_values($res) as $rec)
  {
    $rec = array_values($rec);
    if ($rec[0]==$name) return true;
  }
  return false;
}

function db_dump($fname='db.gz', $include_data = true)
{
  global $__wicked;
  if(!startswith($fname, '/')) $fname = BUILD_FPATH ."/{$fname}";
  ensure_writable_folder(dirname($fname));
  $extra = '';
  if(!$include_data) $extra .= ' --no-data ';
  $d = Wax::$build['database'];
  $cmd = "mysqldump {$extra} --compact -u {$d['username']} --password={$d['password']}  -h {$d['host']}  {$d['catalog']} | gzip > {$fname}";
  wax_exec($cmd);
}

function update_junction($table_name, $left_key_name, $left_key_id, $right_key_name, $right_key_ids)
{
  global $__wicked;
  query("delete from {$table_name} where {$left_key_name} = ?", $left_key_id);
  foreach($right_key_ids as $id)
  {
    query("insert into {$table_name} ({$left_key_name}, {$right_key_name}) values (?, ?)", $left_key_id, $id);
  }
}