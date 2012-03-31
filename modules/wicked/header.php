<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/jquery.validate.js" type="text/javascript"></script>
    <script src="/js/jquery.observe_field.js" type="text/javascript"></script>
    <script src="/js/jquery.youtubepopup.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet"/>
    <title>Online Singing Lessons</title>
    <link href="/modules/<?=$__wicked['theme']?>/style.css" type="text/css" rel="stylesheet"/>
  </head>
<body>
  <div class='widget'>
    <iframe src="http://www.facebook.com/plugins/like.php?href=http://coaching.benallfree.com"
  scrolling="no" frameborder="0"
  style="border:none; width:280px; height:25px; vertical-align: middle"></iframe>
    <? if(is_logged_in()): ?>
      <a href='/account/logout'>Logout</a>
    <? else: ?>
      <a href="/account/login">Login</a> 
      <a href="/account/register">Register</a> 
    <? endif; ?>
  </div>
  <div class="clear"/>
 
  
<div class="nav">
  <div class="title"><a href="/">Online Singing Lessons</a></div>
  <div class="menu">
    <ul>
      <li><a href="/pages/free" style="color: yellow">Free Lessons</a></li>
      <li><a href="/pages/course">Skills Course</a></li>
      <li><a href="/pages/live">Live Lessons</a></li>
      <li><a href="/pages/signup">Signup</a></li>
      <li><a href="/faq">FAQ</a></li>
      <li><a href="/pages/equipment">Equipment</a></li>
      <li><a href="/pages/testimonials">Testimonials</a></li>
    </ul>
  </div>
  <br clear="both"/>
  <? if(is_logged_in()): ?>
    <div class="submenu authenticated">
      <ul>
        <li class='static'>Welcome back, <?=$current_user->login?> --&gt;</li>
        <? if($current_user->access_level>1): ?>
        <li class='teach'><a href="/teach/schedule">Teaching Schedule</a></li>
        <? endif; ?>
        <li><a href="/my/videos">Private Videos</a></li>
        <li><a href="/pages/session">Session Prep</a></li>
        <li><a href="/my/schedule">My Schedule</a></li>
        <li><a href="/my/preferences">My Preferences</a></li>
      </ul>
    </div>
  <? endif; ?>
</div>
<div class='content'>
<? if(has_flash()): ?>
  <div class='flash'>
    <ul>
      <? foreach(get_flash() as $msg): ?>
        <?=h($msg)?><br/>
      <? endforeach ?>
    </ul>
  </div>
<? endif; ?>
<? if(has_error()): ?>
  <div class='error'>
    <ul>
      <? foreach(get_error() as $msg): ?>
        <?=h($msg)?><br/>
      <? endforeach ?>
    </ul>
  </div>
<? endif; ?>
<div class="<?=$this_module_name?>_container">
<? $classes = explode('/',$route_path); ?>
<? foreach($classes as $c): ?>
<div class="<?=$c?>_container">
<? endforeach; ?>
