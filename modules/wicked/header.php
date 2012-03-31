<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet"/>
    <?
    $vpaths = apply_filters('scripts', array());
    ?>
    <? foreach($vpaths as $vpath): ?>
      <script src="<?=$vpath?>" type="text/javascript"></script>
    <? endforeach; ?>
    <title><?=apply_filters('window_title', $__wicked['app_title'])?></title>
    <link href="/modules/<?=$__wicked['theme']?>/style.css" type="text/css" rel="stylesheet"/>
  </head>
<body>
  <div class='widget'>
    <? action('render_widgets') ?>
    <? if(is_logged_in()): ?>
      <a href='/account/logout'>Logout</a>
    <? else: ?>
      <a href="/account/login">Login</a> 
      <a href="/account/register">Register</a> 
    <? endif; ?>
  </div>
  <div class="clear"/>
 
  
<div class="nav">
  <div class="title"><a href="/"><?=apply_filters('page_title', $__wicked['app_title'])?></a></div>
  <div class="menu">
    <?
    $links = apply_filters('nav_links', array());
    ?>
    <? if(count($links)>0): ?>
      <ul>
        <? foreach($links as $link): ?>
          <li><a href="<?=$link['href']?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>"><?=h($link['title'])?></a></li>
        <? endforeach; ?>
      </ul>
    <? endif; ?>
  </div>
  <br clear="both"/>
  <?
  $links = apply_filters('subnav_links', array());
  ?>
  <? if(count($links)>0): ?>
    <div class="submenu authenticated">
      <ul>
        <? foreach($links as $link): ?>
          <li><a href="<?=$link['href']?>" style="<? isset($link['style']) ? $link['style'] : '' ?>"><?=h($link['title'])?></a></li>
        <? endforeach; ?>
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
