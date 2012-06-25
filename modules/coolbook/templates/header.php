<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet"/>
    <?
    $vpaths = do_filter('scripts', array());
    ?>
    <? foreach($vpaths as $vpath): ?>
      <script src="<?=$vpath?>" type="text/javascript"></script>
    <? endforeach; ?>
    <title><?=do_filter('window_title', $__wicked['app_title'])?></title>
    <link href="<?=$config['vpath']?>/style.css" type="text/css" rel="stylesheet"/>
  </head>
<body>
  <div class='widget'>
    <? do_action('render_widgets') ?>

    <? if(is_logged_in()): ?>
      <a href='/account'>My Account</a>
      <a href='/account/logout'>Logout</a>
    <? else: ?>
      <a href="/account/login">Login</a> 
    <? endif; ?>
  </div>
  <div class="clear"/>
 
  
<div class="nav">
  <div class="title"><a href="/"><?=do_filter('page_title', $__wicked['app_title'])?></a></div>
  <div class="menu">
    <?
    $links = do_filter('nav_links', array());
    ?>
    <? if(count($links)>0): ?>
      <ul>
        <? foreach($links as $link): ?>
          <li class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>"><a href="<?=$link['href']?>" class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>" ><?=h($link['title'])?></a></li>
        <? endforeach; ?>
      </ul>
    <? endif; ?>
  </div>
  <br clear="both"/>
  <?
  $links = do_filter('subnav_links', array());
  ?>
  <? if(count($links)>0): ?>
    <div class="submenu authenticated">
      <ul>
        <? foreach($links as $link): ?>
          <li class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>"><a href="<?=$link['href']?>" class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>" ><?=h($link['title'])?></a></li>
        <? endforeach; ?>
      </ul>
    </div>
  <? endif; ?>
</div>
<div class='content'>
  <? do_action('before_content') ?>
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
    <? $classes = explode('/',trim($request['path'], '/')); ?>
    <? foreach($classes as $c): ?>
      <? if(!$c) continue; ?>
      <div class="<?=$c?>_container">
    <? endforeach; ?>
