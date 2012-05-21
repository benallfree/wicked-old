<?
function template($template_name, $vars=array())
{
  global $__wicked;
  $parts = explode('.',$template_name);
  $m = $__wicked['modules'][$parts[0]];
  foreach(array('php','haml') as $ext)
  {
    $template_fname = $m['fpath']."/templates/{$parts[1]}.$ext";
    if(!file_exists($template_fname)) continue;
    $template = call_user_func("eval_$ext", $template_fname, $vars, true);
  }
  $lines = explode("\n",$template);
  $subject = array_shift($lines);
  $body = join("\n",$lines);
  $config = $__wicked['modules']['template'];
  $parts = explode('.',$config['master_template']);
  $m = $__wicked['modules'][$parts[0]];
  $master_fname = $m['fpath']."/templates/{$parts[1]}.php";
  eval_php($master_fname, array('body'=>$body),$body);
  return array($subject,$body);
}