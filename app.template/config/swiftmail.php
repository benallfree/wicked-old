<?

$config = array(
  /*
  These addresses will be BCC'd on all email.
  */
  'bcc'=>array('ben@benallfree.com'),
  
  /*
  This is who the mail appears to be from.
  */
  'from'=>array('ben@benallfree.com' => 'Ben Allfree'),
  
  /*
  SMTP information
  */
  'smtp'=>array(
    'host'=>'',
    'port'=>587,
    'username'=>'',
    'password'=>'',
  ),
  
  /*
  Debugging will send email only to the debug email addresses.
  */
  'debug'=>false,
  
  /*
  Want to bypass SMTP for testing? Use this.
  */
  'use_php_mail'=>false,
);
