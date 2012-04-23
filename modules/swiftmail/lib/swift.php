<?

require_once 'swift/swift_required.php';

function swiftmail($to, $subject=null, $body=null, $send_immediately = null)
{
  global $__wicked;
  $config = $__wicked['modules']['swiftmail']['config'];
  if($send_immediately===null) $send_immediately = $config['send_immediately'];

  $data = array(
    'to'=>$to,
    'subject'=>$subject,
    'body'=>$body,
  );
  $item = MailQueueItem::create( array(
    'attributes'=>array(
      'data'=>json_encode($data),
    ),
  ));
  if($send_immediately)
  {
    return swiftmail_send_queue_item($item);
  }
}

function swiftmail_send_queue_item($i)
{
  $data = json_decode($i->data, true);
  swiftmail_lowlevel_send($data);
  $i->sent_at = time();
  $i->save();
}

function swiftmail_send_queue()
{
  $items = MailQueueItem::find_all( array(
    'conditions'=>array('sent_at is null'),
    'limit'=>100,
  ));
  foreach($items as $i)
  {
    swiftmail_send_queue_item($i);
  }
}

function swiftmail_lowlevel_send($data)
{
  global $__wicked;
  $config = $__wicked['modules']['swiftmail']['config'];

  extract($data);
  extract($config['smtp']);
  
  if($config['debug'])
  {
    $to = $config['debug_email'];
    $subject = "DEBUG MODE - {$subject}";
  }
  if(!is_array($to)) $to = array($to);

  if($config['use_php_mail'])
  {
    $headers = array();
    foreach($config['from'] as $email=>$name)
    {
      $headers[] = sprintf("From: %s <%s>", $name, $email);
      $headers[] = sprintf("Bcc: %s", join(',', $config['bcc']));
    }
    $headers = join("\r\n", $headers);
    
    mail($to[0], $subject, $body, $headers);
    return;
  }

  // Create the Transport
  $transport = Swift_SmtpTransport::newInstance($host, $port)
    ->setUsername($username)
    ->setPassword($password)
    ;
  
  // Create the Mailer using your created Transport
  $mailer = Swift_Mailer::newInstance($transport);
  
  // Construct message
  $message = Swift_Message::newInstance($subject)
  ->setTo($to)
  ->setBody($body)
  ;

  $message->setFrom($config['from'])
  ->setBcc($config['bcc']);

  // Send  
  $result = $mailer->send($message);
  return $result;
}