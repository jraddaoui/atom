<ul class="nav nav-tabs">
  <li class="nav-item active">
    <?php if ($relatedIoCount): ?>
      <?php echo link_to(__('Related Descriptions') . sprintf(' (%d)', $relatedIoCount), array($resource, 'module' => 'term', 'action' => 'index'), array('class' => 'nav-link')) ?>
    <?php else: ?>
      <a class="nav-link" href="#"><?php echo __('Related Descriptions') . sprintf(' (%d)', $relatedIoCount) ?></a>
    <?php endif; ?>
  </li>
  <li class="nav-item">
    <?php if ($relatedActorCount): ?>
      <?php echo link_to(__('Related Authorities') . sprintf(' (%d)', $relatedActorCount), array($resource, 'module' => 'term', 'action' => 'relatedAuthorities'), array('class' => 'nav-link')) ?>
    <?php else: ?>
      <a class="nav-link" href="#"><?php echo __('Related Authorities') . sprintf(' (%d)', $relatedActorCount) ?></a>
    <?php endif; ?>
  </li>
</ul>
