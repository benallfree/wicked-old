<?

function dprint($s,$shouldExit=true)
{
  if(!IS_CLI) echo "<pre>";
  ob_start();
  var_dump($s);
  $out = ob_get_contents();
  ob_end_clean();
  if(IS_CLI)
  {
    echo $out;
  } else {
    echo htmlentities($out,ENT_COMPAT,'UTF-8');
  }
  if(!IS_CLI) echo "</pre>";
  if ($shouldExit) wicked_error('Development stop');
}


function wicked_error($err, $data=null)
{
  if ($data)
  {
    if(IS_CLI)
    {
      $err .= s_var_export($data);
    } else {
      $err = $err."<br/><pre>".htmlentities(s_var_export($data))."</pre>";
    }
  }
  if(!IS_CLI)
  {
    echo( "\"><table>");
    echo ("<tr>");
    echo("<td>");
    echo($err);
    echo("</td>");
    echo("</tr>");
  }
  foreach(debug_backtrace() as $trace)
  {
    if(!IS_CLI)
    {
      echo( "<tr>");
      echo( "<td>");
    }
    if (array_key_exists('file', $trace)) echo( htmlentities($trace['file']));
    if(!IS_CLI)
    {
      echo( "</td>");
      echo( "<td>");
    } else {
      echo "\t";
    }
    if (array_key_exists('line', $trace)) echo( htmlentities($trace['line']));
    if(!IS_CLI)
    {
      echo( "</td>");
      echo( "<td>");
    } else {
      echo "\t";
    }
    if (array_key_exists('function', $trace)) echo( htmlentities($trace['function']));
    if(!IS_CLI)
    {
      echo( "</td>");
      echo( "</tr>");
    } else {
      echo "\n";
    }
  }
  if(!IS_CLI) echo( "</table>");
  trigger_error($err, E_USER_ERROR);
}

function s_var_export($v)
{
  ob_start();
  var_export($v);
  $s = ob_get_contents();
  ob_end_clean();
  return $s;
}