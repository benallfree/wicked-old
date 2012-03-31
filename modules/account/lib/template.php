<?
function account_template($template_name, $vars=array())
{
  $template_fname = dirname(__FILE__)."/../templates/{$template_name}.php";
  $template = eval_php($template_fname, $vars, true);
  $lines = explode("\n",$template);
  $subject = array_shift($lines);
  $body = join("\n",$lines);
  $master_fname = dirname(__FILE__)."/../templates/master.php";
  $body = eval_php($master_fname, array('body'=>$body),true);
  return array($subject,$body);
}