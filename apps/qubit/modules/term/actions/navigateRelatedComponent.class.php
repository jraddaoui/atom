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

class TermNavigateRelatedComponent extends sfComponent
{
  // Arrays not allowed in class constants
  public static
    $TAXONOMY_ES_FIELD = array(
      QubitTaxonomy::PLACE_ID   => 'places.id',
      QubitTaxonomy::SUBJECT_ID => 'subjects.id',
      QubitTaxonomy::GENRE_ID   => 'genres.id'
    );

  public function execute($request)
  {
    if (!isset(self::$TAXONOMY_ES_FIELD[$this->resource->taxonomyId]))
    {
      return sfView::NONE;
    }

    // Take note of number of related descriptions
    $resultSet = self::getEsResultsRelatedToTerm('QubitInformationObject', $this->resource);
    $this->relatedIoCount = $resultSet->count();

    // Take note of number of related actors
    $resultSet = self::getEsResultsRelatedToTerm('QubitActor', $this->resource);
    $this->relatedActorCount = $resultSet->count();
  }

  static function getEsResultsRelatedToTerm($relatedModelClass, $term, $search = null)
  {
    if (!isset(self::$TAXONOMY_ES_FIELD[$term->taxonomyId]))
    {
      throw new sfException('Unsupported taxonomy.');
    }

    // Search for related resources
    $search = (!empty($search)) ? $search : new arElasticSearchPluginQuery();

    $query = new \Elastica\Query\Term;
    $query->setTerm(self::$TAXONOMY_ES_FIELD[$term->taxonomyId], $term->id);
    $search->query->setQuery($search->queryBool->addMust($query));

    // Filter out drafts if querying descriptions
    if ($relatedModelClass == 'QubitInformationObject')
    {
      QubitAclSearch::filterDrafts($search->queryBool);
    }

    return QubitSearch::getInstance()->index->getType($relatedModelClass)->search($search->getQuery(false));
  }
}
