<?php

/**
 * @file jcarousel-view.tpl.php
 * View template to display a list as a carousel.
 */
?>
<ul class="<?php print $jcarousel_classes; ?>">

  <?php $i = 0; $numItem = 4; $n = count($rows); ?>
  
  <?php while ($i < $n): ?>
  <li class="<?php print $row_classes[$i]; ?>">
    <?php for ($j = 0; $j < $numItem; $j++): ?>
      <?php if ($i + $j < $n): ?>
      <div class="view-item">
        <?php print $rows[$i+$j]; ?>
      </div>
      <?php endif; ?>
    <?php endfor; ?>
  </li>
  <?php $i += $numItem; ?>
  <?php endwhile; ?>
  
</ul>
