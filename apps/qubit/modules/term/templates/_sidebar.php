<div class="sidebar-lowering-sort">

  <?php if (!$showTreeview): ?>
    <?php echo get_component('term', 'treeView', array('browser' => false)) ?>
  <?php else: ?>

    <?php echo get_component('term', 'treeView', array('browser' => false, 'tabs' => true, 'pager' => $listPager)) ?>

    <section id="facets">

      <div class="visible-phone facets-header">
        <a class="x-btn btn-wide">
          <i class="fa fa-filter"></i>
          <?php echo __('Filters') ?>
        </a>
      </div>

      <div class="content">

        <?php echo get_partial('search/aggregation', array(
          'id' => '#facet-languages',
          'label' => __('Language'),
          'name' => 'languages',
          'aggs' => $aggs,
          'filters' => $search->filters)) ?>

        <?php if (QubitTaxonomy::PLACE_ID != $resource->taxonomyId): ?>
          <?php echo get_partial('search/aggregation', array(
            'id' => '#facet-places',
            'label' => sfConfig::get('app_ui_label_place'),
            'name' => 'places',
            'aggs' => $aggs,
            'filters' => $search->filters)) ?>
          <?php endif; ?>

        <?php if (QubitTaxonomy::SUBJECT_ID != $resource->taxonomyId): ?>
          <?php echo get_partial('search/aggregation', array(
            'id' => '#facet-subjects',
            'label' => sfConfig::get('app_ui_label_subject'),
            'name' => 'subjects',
            'aggs' => $aggs,
            'filters' => $search->filters)) ?>
        <?php endif; ?>

        <?php if (QubitTaxonomy::GENRE_ID != $resource->taxonomyId): ?>
          <?php echo get_partial('search/aggregation', array(
            'id' => '#facet-genres',
            'label' => sfConfig::get('app_ui_label_genre'),
            'name' => 'genres',
            'aggs' => $aggs,
            'filters' => $search->filters)) ?>
        <?php endif; ?>

      </div>

    </section>

  <?php endif; ?>

</div>
