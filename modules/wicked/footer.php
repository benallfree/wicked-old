<? foreach($classes as $c): ?>
</div>
<? endforeach; ?>
</div>
<br style="clear:both"/>
</div>
<div class="footer">
  <p>Copyright 2012, Ben Allfree</p>
  <ul>
        <li><a href="/about">About</a></li>
        <li><a href="/how">How it Works</a></li>
        <li><a href="/ben">Meet Your Coach</a></li>
  </ul>
  <br clear="both"/>
</div>

<script type="text/javascript">
  $(function () {
    $("a.youtube").YouTubePopup({ autoplay: 0 });

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