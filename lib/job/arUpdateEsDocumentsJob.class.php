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
 * Updates information object documents in the Elasticsearch index
 *
 * @package    symfony
 * @subpackage jobs
 */

class arUpdateEsDocumentsJob extends arBaseJob
{
  /**
   * @see arBaseJob::$requiredParameters
   */
  protected $extraRequiredParameters = ['ids', 'updateResource', 'updateDescendants'];

  public function runJob($parameters)
  {
    if (empty($parameters['ids']) || empty($parameters['modelClass']) || (!$parameters['updateResource'] && !$parameters['updateDescendants']))
    {
      $this->error($this->i18n->__('Called arUpdateEsDocumentsJob without specifying what needs to be updated.'));

      return false;
    }

    $resourceNames = [
      'QubitActor'             => 'authority record',
      'QubitInformationObject' => 'description'
    ];

    $resourceName = $resourceNames[$parameters['modelClass']];

    if ($parameters['updateResource'] && $parameters['updateDescendants'])
    {
      $message = $this->i18n->__('Updating %1 %2(s) and their descendants.', ['%1' => count($parameters['ids']), '%2' => $resourceName]);
    }
    elseif ($parameters['updateResource'])
    {
      $message = $this->i18n->__('Updating %1 %2(s).', ['%1' => count($parameters['ids']), '%2' => $resourceName]);
    }
    else
    {
      $message = $this->i18n->__('Updating descendants of %1 %2(s).', ['%1' => count($parameters['ids']), '%2' => $resourceName]);
    }

    $this->job->addNoteText($message);
    $this->info($message);

    $count = 0;
    foreach ($parameters['ids'] as $id)
    {
      if (null === $object = $parameters['modelClass']::getById($id))
      {
        $this->info($this->i18n->__('Invalid archival %1 id: %2', ['%1' => $resourceName, '%2' => $id]));

        continue;
      }
      
      // Don't count invalid IDs
      $count++;

      if ($parameters['updateResource'] && $parameters['updateDescendants'])
      {
        arElasticSearchInformationObject::update($object, ['updateDescendants' => true]);
        $message = $this->i18n->__('Updated %1 %2(s) and their descendants.', ['%1' => $count, '%2' => $resourceName]);
      }
      elseif ($parameters['updateResource'])
      {
        arElasticSearchInformationObject::update($object);
        $message = $this->i18n->__('Updated %1 %2(s).', ['%1' => $count, '%2' => $resourceName]);
      }
      else
      {
        arElasticSearchInformationObject::updateDescendants($object);
        $message = $this->i18n->__('Updating descendant of %1 %2(s).', ['%1' => $count, '%2' => $resourceName]);
      }

      // Minimize memory use in case we're dealing with a large number of information objects
      Qubit::clearClassCaches();

      // Status update every 100 resources
      if (0 == $count % 100)
      {
        $this->info($message);
      }
    }

    // Final status update, if total count is not a multiple of 100
    if (0 != $count % 100)
    {
      $this->info($message);
    }

    $this->job->setStatusCompleted();
    $this->job->save();

    return true;
  }
}
