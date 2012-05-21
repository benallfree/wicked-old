    <? $classes = explode('/',trim($request['path'], '/')); ?>
    <? foreach($classes as $c): ?>
      <? if(!$c) continue; ?>
      </div>
    <? endforeach; ?>
  </div>
  <br style="clear:both"/>
  <? do_action('after_content') ?>
</div>
<div class="footer">
  <? do_action('before_footer') ?>
  <?
  $links = do_filter('footer_links', array());
  ?>
  <? if(count($links)>0): ?>
    <ul>
      <? foreach($links as $link): ?>
        <li class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>">
          <? if(isset($link['href'])): ?>
            <a href="<?=$link['href']?>" class="<?= isset($link['class']) ? $link['class'] : '' ?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>" >
          <? endif; ?>
          <?=h($link['title'])?>
          <? if(isset($link['href'])): ?>
            </a>
          <? endif; ?>
        </li>
      <? endforeach; ?>
    </ul>
  <? endif; ?>
  <br clear="both"/>
  <? do_action('after_footer') ?>
</div>
<script type="text/javascript">
  $(function () {
    $('.button').click(function() {
      e = $(this).parents('form');        
      if( $(this).attr('href')==undefined && e.length==0 ) return true;
      $(this).addClass('loading');
      $(this).html("Please wait...");
      $(this).off('click');
      if($(this).attr('href')!=undefined)
      {
        window.location=$(this).attr('href');
      } else {
        e.submit();
      }
    });

  });
</script>

</body>
</html>