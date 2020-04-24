<?php

/*
 * This file is part of the Access to Memory (AtoM) software.
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Show paginated list of actors.
 *
 * @package    AccesstoMemory
 * @subpackage actor
 * @author     Peter Van Garderen <peter@artefactual.com>
 * @author     David Juhasz <david@artefactual.com>
 * @author     Wu Liu <wu.liu@usask.ca>
 */
class TermRelatedAuthoritiesAction extends DefaultBrowseAction
{
  const INDEX_TYPE = 'QubitActor';

  // Arrays not allowed in class constants
  public static
    $FILTERTAGS = array(),

    $AGGS = array(
      'languages' =>
        array('type' => 'term',
              'field' => 'i18n.languages',
              'size' => 10),
      'occupation' =>
        array('type' => 'term',
              'field' => 'occupations.id',
              'size' => 10),
      'place' =>
        array('type'   => 'term',
              'field'  => 'places.id',
              'size'   => 10),
      'subject' =>
        array('type'   => 'term',
              'field'  => 'subjects.id',
              'size'   => 10));

  protected function populateAgg($name, $buckets)
  {
    switch ($name)
    {
      case 'occupation':
      case 'place':
      case 'subject':
        $ids = array_column($buckets, 'key');
        $criteria = new Criteria;
        $criteria->add(QubitTerm::ID, $ids, Criteria::IN);

        foreach (QubitTerm::get($criteria) as $item)
        {
          $buckets[array_search($item->id, $ids)]['display'] = $item->getName(array('cultureFallback' => true));
        }

        break;

      default:
        return parent::populateAgg($name, $buckets);
    }

    return $buckets;
  }

  protected function setSort($request)
  {
    switch ($request->sort)
    {
      // I don't think that this is going to scale, but let's leave it for now
      case 'alphabetic':
        $field = sprintf('i18n.%s.authorizedFormOfName.alphasort', $this->selectedCulture);
        $this->search->query->setSort(array($field => $request->sortDir));

        break;

      case 'identifier':
        $this->search->query->setSort(array('descriptionIdentifier.untouched' => $request->sortDir));

        break;

      case 'lastUpdated':
      default:
        $this->search->query->setSort(array('updatedAt' => $request->sortDir));
    }
  }

  protected function doSearch($request)
  {
    $this->setSort($request);
    return TermNavigateRelatedComponent::getEsResultsRelatedToTerm('QubitActor', $this->resource, $this->search);
  }

  public function execute($request)
  {
    parent::execute($request);

    $this->resource = $this->getRoute()->resource;
    if (!$this->resource instanceof QubitTerm)
    {
      $this->forward404();
    }
 
    // Check that this isn't the root
    if (!isset($this->resource->parent))
    {
      $this->forward404();
    }

    // Disallow access to locked taxonomies
    if (in_array($this->resource->taxonomyId, QubitTaxonomy::$lockedTaxonomies))
    {
      $this->getResponse()->setStatusCode(403);
      return sfView::NONE;
    }
 
    if (isset($request->languages))
    {
      $this->culture = $request->languages;
    }
    else
    {
      $this->culture = $this->context->user->getCulture();
    }

    // Prepare filter tags, form, and hidden fields/values
    $this->populateFilterTags($request);

    // Take note of number of related information objects
    $resultSet = TermNavigateRelatedComponent::getEsResultsRelatedToTerm('QubitInformationObject', $this->resource);
    $this->relatedIoCount = $resultSet->count();

    // Perform search and paging
    $resultSet = $this->doSearch($request);
    $this->relatedActorCount = $resultSet->count();

    $this->pager = new QubitSearchPager($resultSet);
    $this->pager->setPage($request->page ? $request->page : 1);
    $this->pager->setMaxPerPage($this->limit);
    $this->pager->init();

    $this->populateAggs($resultSet);
  }
}
