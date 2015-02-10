<div id="page" class="page-default"> <a name="Top" id="Top"></a>
  <div id="main-wrapper" class="wrapper">
    <div class="container">
      <div class="row"> 
        <div id="main-content" class="span12">
          <div class="grid-inner clearfix">
            <?php print render($title_prefix); ?>
              <h1 id="page-title"><span><?php print $title; ?></span></h1>
            <?php print render($title_suffix); ?>
            <?php if ($content = render($page['content'])): print $content; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
