<?
function template($template_name, $vars=array())
{
  global $__wicked;
  $parts = explode('.',$template_name);
  $m = $__wicked['modules'][$parts[0]];
  $template_fname = $m['config']['fpath']."/templates/{$parts[1]}.php";
  $template = eval_php($template_fname, $vars, true);
  $lines = explode("\n",$template);
  $subject = array_shift($lines);
  $body = join("\n",$lines);
  $config = $__wicked['modules']['template']['config'];
  $parts = explode('.',$config['master_template']);
  $m = $__wicked['modules'][$parts[0]];
  $master_fname = $m['config']['fpath']."/templates/{$parts[1]}.php";
  $body = eval_php($master_fname, array('body'=>$body),true);
  return array($subject,$body);
}