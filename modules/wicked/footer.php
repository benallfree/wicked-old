<? foreach($classes as $c): ?>
</div>
<? endforeach; ?>
</div>
<br style="clear:both"/>
</div>
<div class="footer">
  <? action('footer') ?>
  <?
  $links = apply_filters('footer_links', array());
  ?>
  <? if(count($links)>0): ?>
    <ul>
      <? foreach($links as $link): ?>
        <li><a href="<?=$link['href']?>" style="<?= isset($link['style']) ? $link['style'] : '' ?>"><?=h($link['title'])?></a></li>
      <? endforeach; ?>
    </ul>
  <? endif; ?>
  <br clear="both"/>
</div>
<? action('after_footer') ?>
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