<?php decorate_with('layout_3col') ?> 
<?php use_helper('Date') ?>

<?php slot('sidebar') ?>

  <?php echo get_partial('term/sidebar', array(
    'resource' => $resource,
    'showTreeview' => $addBrowseElements,
    'search' => $search,
    'aggs' => $aggs,
    'listPager' => $listPager)) ?>

<?php end_slot() ?>

<?php slot('title') ?>

  <h1><?php echo render_title($resource) ?></h1>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <?php echo link_to(__('Related Descriptions'), array($resource, 'module' => 'term', 'action' => 'index'), array('class' => 'nav-link')) ?>
    </li>
    <li class="nav-item active">
      <a class="nav-link" href="#"><?php echo __('Related Authorities') ?></a>
    </li>
  </ul>

  <?php echo get_partial('term/errors', array('errorSchema' => $errorSchema)) ?>

  <?php if (QubitTerm::ROOT_ID != $resource->parentId): ?>
    <?php echo include_partial('default/breadcrumb', array('resource' => $resource, 'objects' => $resource->getAncestors()->andSelf()->orderBy('lft'))) ?>
  <?php endif; ?>

<?php end_slot() ?>

<?php slot('before-content') ?>
  <?php echo get_component('default', 'translationLinks', array('resource' => $resource)) ?>
<?php end_slot() ?>

<?php slot('context-menu') ?>

  <div class="sidebar">
    <?php echo get_partial('term/format', array('resource' => $resource)) ?>

    <?php echo get_partial('term/rightContextMenu', array('resource' => $resource, 'results' => $pager->getNbResults())) ?>
  </div>

<?php end_slot() ?>

<?php slot('content') ?>

  <div id="content">
    <?php echo get_partial('term/fields', array('resource' => $resource)) ?>
  </div>

  <?php echo get_partial('term/actions', array('resource' => $resource)) ?>

  <h1><?php echo __('%1% %2% results for %3%', array('%1%' => $pager->getNbResults(), '%2%' => sfConfig::get('app_ui_label_actor'), '%3%' => render_title($resource))) ?></h1>

  <section class="header-options">
    <!-- TODO: onlyDirect support? -->

    <div class="pickers">
      <?php echo get_partial('default/sortPickers',
        array(
          'options' => array(
            'lastUpdated' => __('Date modified'),
            'alphabetic'  => __('Name'),
            'identifier'  => __('Identifier')))) ?>
    </div>
  </section>

  <div id="content">

    <!-- TODO: onlyDirect support? -->

    <?php if ($pager->getNbResults()): ?>

      <?php foreach ($pager->getResults() as $hit): ?>
        <?php $doc = $hit->getData() ?>
        <?php echo include_partial('actor/searchResult', array('doc' => $doc, 'pager' => $pager, 'culture' => $selectedCulture)) ?>
      <?php endforeach; ?>

    <?php else: ?>

      <div>
        <h2><?php echo __('We couldn\'t find any results matching your search.') ?></h2>
      </div>

    <?php endif; ?>

  </div>

<?php end_slot() ?>

<?php slot('after-content') ?>
  <?php echo get_partial('default/pager', array('pager' => $pager)) ?>
<?php end_slot() ?>
