<?php echo '<?xml version="1.0" encoding="'.sfConfig::get('sf_charset', 'UTF-8')."\" ?>\n" ?>
<!DOCTYPE ead PUBLIC "+//ISBN 1-931666-00-8//DTD ead.dtd (Encoded Archival Description (EAD) Version 2002)//EN" "http://lcweb2.loc.gov/xmlcommon/dtds/ead2002/ead.dtd">

<ead>
<eadheader langencoding="iso639-2b" countryencoding="iso3166-1" dateencoding="iso8601" repositoryencoding="iso15511" scriptencoding="iso15924" relatedencoding="DC">
  <?php echo $ead->renderEadId() ?>
  <filedesc>
    <titlestmt>
<?php if (0 < strlen($value = $resource->getTitle(array('cultureFallback' => true)))): ?>
      <titleproper encodinganalog="Title"><?php echo esc_specialchars($value) ?></titleproper>
<?php endif; ?>
<?php if (0 < count($archivistsNotes = $resource->getNotesByType(array('noteTypeId' => QubitTerm::ARCHIVIST_NOTE_ID)))): ?>
<?php foreach ($archivistsNotes as $note): ?>
      <author encodinganalog="Creator"><?php echo esc_specialchars($note) ?></author>
<?php endforeach; ?>
<?php endif; ?>
    </titlestmt>
<?php
  // TODO: find out if we need this element as it's not imported by our EAD importer
  if (0 < strlen($value = $resource->getEdition(array('cultureFallback' => true)))):
?>
    <editionstmt>
      <edition><?php echo esc_specialchars($value) ?></edition>
    </editionstmt>
<?php endif; ?>
<?php if ($value = $resource->getRepository()): ?>
    <publicationstmt>
      <publisher encodinganalog="Publisher"><?php echo esc_specialchars($value->__toString()) ?></publisher>
<?php if ($address = $value->getPrimaryContact()): ?>
      <address>
<?php if (0 < strlen($addressline = $address->getStreetAddress())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getCity())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getRegion())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $resource->getRepositoryCountry())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getPostalCode())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getTelephone())): ?>
        <addressline><?php echo __('Telephone: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getFax())): ?>
        <addressline><?php echo __('Fax: ').esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getEmail())): ?>
        <addressline><?php echo __('Email: ').esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getWebsite())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
      </address>
<?php endif; ?>
      <date normal="<?php echo $publicationDate ?>" encodinganalog="Date"><?php echo esc_specialchars($publicationDate) ?></date>
    </publicationstmt><?php endif; ?>
  </filedesc>
  <profiledesc>
    <creation>
      <?php echo __("Generated by %1% %2%\n", array('%1%' => $sf_context->getConfiguration()->getApplication(), '%2%' => qubitConfiguration::VERSION)) ?>
      <date normal="<?php echo gmdate('o-m-d') ?>"><?php echo gmdate('o-m-d H:s:e') ?></date>
    </creation>
    <langusage>
<?php if ($exportLanguage != $sourceLanguage): ?>
      <language langcode="<?php echo ($iso6392 = $iso639convertor->getID3($exportLanguage)) ? strtolower($iso6392) : $exportLanguage ?>" encodinganalog="Language"><?php echo format_language($exportLanguage) ?></language><?php endif; ?>
      <language langcode="<?php echo ($iso6392 = $iso639convertor->getID3($sourceLanguage)) ? strtolower($iso6392) : $sourceLanguage ?>" encodinganalog="Language"><?php echo format_language($sourceLanguage) ?></language>
    </langusage>
<?php if (0 < strlen($rules = $resource->getRules(array('cultureFallback' => true)))): ?>
    <descrules encodinganalog="3.7.2"><?php echo esc_specialchars($rules) ?></descrules>
<?php endif; ?>
  </profiledesc>
</eadheader>
<!-- TODO: <frontmatter></frontmatter> -->
<archdesc <?php if ($resource->levelOfDescriptionId):?>level="<?php if (in_array(strtolower($levelOfDescription = $resource->getLevelOfDescription()->getName(array('culture' => 'en'))), $eadLevels)): ?><?php echo strtolower($levelOfDescription).'"' ?><?php else: ?><?php echo 'otherlevel" otherlevel="'.$levelOfDescription.'"' ?><?php endif; ?><?php endif; ?> relatedencoding="ISAD(G)v2">
  <did>
<?php if (0 < strlen($value = $resource->getPropertyByName('titleProperOfPublishersSeries')->__toString())): ?>
    <unittitle><bibseries><title><?php echo esc_specialchars($value) ?></title></bibseries></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('parallelTitleOfPublishersSeries')->__toString())): ?>
    <unittitle><bibseries><title type="parallel"><?php echo esc_specialchars($value) ?></title></bibseries></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('otherTitleInformationOfPublishersSeries')->__toString())): ?>
    <unittitle><bibseries><title type="otherinfo"><?php echo esc_specialchars($value) ?></title></bibseries></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('statementOfResponsibilityRelatingToPublishersSeries')->__toString())): ?>
    <unittitle><bibseries><title type="statrep"><?php echo esc_specialchars($value) ?></title></bibseries></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('numberingWithinPublishersSeries')->__toString())): ?>
    <unittitle><bibseries><num><?php echo esc_specialchars($value) ?></num></bibseries></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getTitle(array('cultureFallback' => true)))): ?>
    <unittitle encodinganalog="3.1.2"><?php echo esc_specialchars($value) ?></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->alternateTitle)): ?>
      <unittitle type="parallel"><?php echo esc_specialchars($value) ?></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('otherTitleInformation')->__toString())): ?>
      <unittitle type="otherinfo"><?php echo esc_specialchars($value) ?></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('titleStatementOfResponsibility')->__toString())): ?>
      <unittitle type="statrep"><?php echo esc_specialchars($value) ?></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getEdition(array('cultureFallback' => true)))): ?>
      <unittitle><edition><?php echo esc_specialchars($value) ?></edition></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('editionStatementOfResponsibility')->__toString())): ?>
      <unittitle type="statrep"><edition><?php echo esc_specialchars($value) ?></edition></unittitle>
<?php endif; ?>
<?php if (0 < strlen($resource->getIdentifier())): ?>
    <unitid <?php if ($resource->getRepository()): ?><?php if ($repocode = $resource->getRepository()->getIdentifier()): ?><?php echo 'repositorycode="'.esc_specialchars($repocode).'" ' ?><?php endif; ?><?php if ($countrycode = $resource->getRepository()->getCountryCode()): ?><?php echo 'countrycode="'.$countrycode.'"' ?><?php endif;?><?php endif; ?> encodinganalog="3.1.1"><?php echo esc_specialchars($ead->referenceCode) ?></unitid>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPropertyByName('standardNumber')->__toString())): ?>
    <unitid type="standard"><?php echo esc_specialchars($value) ?></unitid>
<?php endif; ?>
<?php foreach ($resource->getDates() as $date): ?>
    <unitdate <?php if ($type = $date->getType()->__toString()): ?><?php echo 'datechar="'.strtolower($type).'" ' ?><?php endif; ?><?php if ($startdate = $date->getStartDate()): ?><?php echo 'normal="'?><?php echo Qubit::renderDate($startdate) ?><?php if (0 < strlen($enddate = $date->getEndDate())): ?><?php echo '/'?><?php echo Qubit::renderDate($enddate) ?><?php endif; ?><?php echo '"' ?><?php endif; ?> encodinganalog="3.1.3"><?php echo esc_specialchars(Qubit::renderDateStartEnd($date->getDate(array('cultureFallback' => true)), $date->startDate, $date->endDate)) ?></unitdate>
<?php endforeach; // dates ?>
<?php if (0 < count($creators = $resource->getCreators())): ?>
    <origination encodinganalog="3.2.1">
<?php foreach ($creators as $creator): ?>
<?php if ($type = $creator->getEntityTypeId()): ?>
<?php if ($type == QubitTerm::PERSON_ID): ?>
      <persname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></persname>
<?php endif; ?>
<?php if ($type == QubitTerm::FAMILY_ID): ?>
      <famname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></famname>
<?php endif; ?>
<?php if ($type == QubitTerm::CORPORATE_BODY_ID): ?>
      <corpname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></corpname>
<?php endif; ?>
<?php else: ?>
      <name><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></name>
<?php endif; ?>
<?php endforeach; ?>
    </origination>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getExtentAndMedium(array('cultureFallback' => true)))): ?>
    <physdesc>
      <extent encodinganalog="3.1.5"><?php echo esc_specialchars($value) ?></extent>
    </physdesc>
<?php endif; ?>
<?php if ($value = $resource->getRepository()): ?>
    <repository>
      <corpname><?php echo esc_specialchars($value->__toString()) ?></corpname>
<?php if ($address = $value->getPrimaryContact()): ?>
      <address>
<?php if (0 < strlen($addressline = $address->getStreetAddress())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getCity())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getRegion())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $resource->getRepositoryCountry())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getPostalCode())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getTelephone())): ?>
        <addressline><?php echo __('Telephone: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getFax())): ?>
        <addressline><?php echo __('Fax: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getEmail())): ?>
        <addressline><?php echo __('Email: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getWebsite())): ?>
        <addressline><?php echo esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
      </address>
<?php endif; ?>
    </repository>
<?php endif; ?>
<?php if (0 < count($langmaterial = $resource->language)): ?>
  <langmaterial encodinganalog="3.4.3">
<?php foreach ($langmaterial as $languageCode): ?>
    <language langcode="<?php echo ($iso6392 = $iso639convertor->getID3($languageCode)) ? strtolower($iso6392) : $languageCode ?>"><?php echo format_language($languageCode) ?></language><?php endforeach; ?>
  </langmaterial><?php endif; ?>
<?php if (0 < count($notes = $resource->getNotesByType(array('noteTypeId' => QubitTerm::GENERAL_NOTE_ID)))): ?><?php foreach ($notes as $note): ?><note type="<?php echo esc_specialchars($note->getType(array('cultureFallback' => true))) ?>" encodinganalog="3.6.1"><p><?php echo esc_specialchars($note->getContent(array('cultureFallback' => true))) ?></p></note><?php endforeach; ?><?php endif; ?>
  </did>
<?php
$variationNoteTypeId = QubitFlatfileImport::getTaxonomyTermIdUsingName(
  QubitTaxonomy::RAD_TITLE_NOTE_ID,
  'Variations in title'
);
if (0 < count($variationNotes = $resource->getNotesByType(array('noteTypeId' => $variationNoteTypeId)))): ?>
<?php foreach ($variationNotes as $note): ?>
  <odd type="variation"><p><?php echo esc_specialchars($note) ?></p></odd>
<?php endforeach; ?>
<?php endif; ?>
<?php foreach ($resource->getCreators() as $creator): ?>
<?php if ($value = $creator->getHistory(array('cultureFallback' => true))): ?>
  <bioghist encodinganalog="3.2.2"><p><?php echo esc_specialchars($value) ?></p></bioghist><?php endif; ?><?php endforeach; ?>
<?php if (0 < strlen($value = $resource->getScopeAndContent(array('cultureFallback' => true)))): ?>
  <scopecontent encodinganalog="3.3.1"><p><?php echo esc_specialchars($value) ?></p></scopecontent><?php endif; ?>
<?php if (0 < strlen($value = $resource->getArrangement(array('cultureFallback' => true)))): ?>
  <arrangement encodinganalog="3.3.4"><p><?php echo esc_specialchars($value) ?></p></arrangement><?php endif; ?>
<?php if ((0 < count($materialtypes = $resource->getMaterialTypes())) ||
            (0 < count($subjects = $resource->getSubjectAccessPoints())) ||
            (0 < count($places = $resource->getPlaceAccessPoints())) ||
            (0 < count($resource->getActors()))): ?>
  <controlaccess>
<?php foreach ($resource->getActorEvents() as $event): ?>
<?php if ($event->getActor()->getEntityTypeId() == QubitTerm::PERSON_ID): ?>
    <persname role="<?php echo $event->getType()->getRole(array('cultureFallback' => true)) ?>"><?php echo esc_specialchars(render_title($event->getActor())) ?></persname>
<?php elseif ($event->getActor()->getEntityTypeId() == QubitTerm::FAMILY_ID): ?>
    <famname role="<?php echo $event->getType()->getRole(array('cultureFallback' => true)) ?>"><?php echo esc_specialchars(render_title($event->getActor())) ?></famname>
<?php else: ?>
    <corpname role="<?php echo $event->getType()->getRole(array('cultureFallback' => true)) ?>"><?php echo esc_specialchars(render_title($event->getActor())) ?></corpname>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($materialtypes as $materialtype): ?>
    <genreform><?php echo esc_specialchars($materialtype->getTerm()) ?></genreform>
<?php endforeach; ?>
<?php foreach ($subjects as $subject): ?>
    <subject<?php if ($subject->getTerm()->code):?> authfilenumber="<?php echo $subject->getTerm()->code ?>"<?php endif; ?><?php if ($subject->getTerm()->getSourceNotes()):?> source="<?php foreach ($subject->getTerm()->getSourceNotes() as $note): ?><?php echo $note ?><?php endforeach; ?>"<?php endif; ?>><?php echo esc_specialchars($subject->getTerm()) ?></subject>
<?php endforeach; ?>
<?php foreach ($places as $place): ?>
    <geogname><?php echo esc_specialchars($place->getTerm()) ?></geogname>
<?php endforeach; ?>
  </controlaccess>
<?php endif; ?>
<?php if (0 < strlen($value = $resource->getPhysicalCharacteristics(array('cultureFallback' => true)))): ?>
  <phystech encodinganalog="3.4.3"><p><?php echo esc_specialchars($value) ?></p></phystech><?php endif; ?>
<?php if (0 < strlen($value = $resource->getAppraisal(array('cultureFallback' => true)))): ?>
  <appraisal encodinganalog="3.3.2"><p><?php echo esc_specialchars($value) ?></p></appraisal><?php endif; ?>
<?php if (0 < strlen($value = $resource->getAcquisition(array('cultureFallback' => true)))): ?>
  <acqinfo encodinganalog="3.2.4"><p><?php echo esc_specialchars($value) ?></p></acqinfo><?php endif; ?>
<?php if (0 < strlen($value = $resource->getAccruals(array('cultureFallback' => true)))): ?>
  <accruals encodinganalog="3.3.3"><p><?php echo esc_specialchars($value) ?></p></accruals><?php endif; ?>
<?php if (0 < strlen($value = $resource->getArchivalHistory(array('cultureFallback' => true)))): ?>
  <custodhist encodinganalog="3.2.3"><p><?php echo esc_specialchars($value) ?></p></custodhist><?php endif; ?>
<?php if (0 < strlen($value = $resource->getRevisionHistory(array('cultureFallback' => true)))): ?>
  <processinfo><p><date><?php echo esc_specialchars($value) ?></date></p></processinfo><?php endif; ?>
<?php if (0 < strlen($value = $resource->getLocationOfOriginals(array('cultureFallback' => true)))): ?>
  <originalsloc encodinganalog="3.5.1"><p><?php echo esc_specialchars($value) ?></p></originalsloc><?php endif; ?>
<?php if (0 < strlen($value = $resource->getLocationOfCopies(array('cultureFallback' => true)))): ?>
  <altformavail encodinganalog="3.5.2"><p><?php echo esc_specialchars($value) ?></p></altformavail><?php endif; ?>
<?php if (0 < strlen($value = $resource->getRelatedUnitsOfDescription(array('cultureFallback' => true)))): ?>
  <relatedmaterial encodinganalog="3.5.3"><p><?php echo esc_specialchars($value) ?></p></relatedmaterial><?php endif; ?>
<?php if (0 < strlen($value = $resource->getAccessConditions(array('cultureFallback' => true)))): ?>
  <accessrestrict encodinganalog="3.4.1"><p><?php echo esc_specialchars($value) ?></p></accessrestrict><?php endif; ?>
<?php if (0 < strlen($value = $resource->getReproductionConditions(array('cultureFallback' => true)))): ?>
  <userestrict encodinganalog="3.4.2"><p><?php echo esc_specialchars($value)  ?></p></userestrict><?php endif; ?>
<?php if (0 < strlen($value = $resource->getFindingAids(array('cultureFallback' => true)))): ?>
  <otherfindaid encodinganalog="3.4.5"><p><?php echo esc_specialchars($value) ?></p></otherfindaid><?php endif; ?>
<?php if (0 < count($publicationNotes = $resource->getNotesByType(array('noteTypeId' => QubitTerm::PUBLICATION_NOTE_ID)))): ?><?php foreach ($publicationNotes as $note): ?><bibliography encodinganalog="3.5.4"><p><?php echo esc_specialchars($note) ?></p></bibliography><?php endforeach; ?><?php endif; ?>

  <dsc type="combined">
<?php $nestedRgt = array() ?>
<?php foreach ($resource->getDescendants()->orderBy('lft') as $descendant): ?>
    <c <?php if ($descendant->levelOfDescriptionId):?>level="<?php if (in_array(strtolower($levelOfDescription = $descendant->getLevelOfDescription()->getName(array('culture' => 'en'))), $eadLevels)): ?><?php echo strtolower($levelOfDescription).'"' ?><?php else: ?><?php echo 'otherlevel" otherlevel="'.$levelOfDescription.'"' ?><?php endif; ?><?php endif; ?>>
      <did>
<?php foreach ($descendant->getPhysicalObjects() as $physicalObject): ?>
<?php if ($physicalObject->getLocation(array('cultureFallback' => true))): ?>
        <physloc><?php echo esc_specialchars($physicalObject->getLocation(array('cultureFallback' => true))) ?></physloc>
<?php endif; ?>
<?php if ($physicalObject->getName(array('cultureFallback' => true))): ?>
        <container <?php if ($type = $physicalObject->getType()): ?><?php echo 'type="'.str_replace(' ', '', $physicalObject->getType()).'" ' ?><?php endif; ?>><?php echo esc_specialchars($physicalObject->getName(array('cultureFallback' => true))) ?></container>
<?php endif; ?>
<?php endforeach; ?>
<?php if (0 < strlen($value = $descendant->getTitle(array('cultureFallback' => true)))): ?>
        <unittitle encodinganalog="3.1.2"><?php echo esc_specialchars($value) ?></unittitle>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getIdentifier())): ?>
        <unitid <?php if ($descendant->getRepository()): ?><?php if ($repocode = $descendant->getRepository()->getIdentifier()): ?><?php echo 'repositorycode="'.esc_specialchars($repocode).'" ' ?><?php endif; ?><?php if ($countrycode = $descendant->getRepository()->getCountryCode()): ?><?php echo 'countrycode="'.$countrycode.'"' ?><?php endif;?><?php endif; ?> encodinganalog="3.1.1"><?php echo esc_specialchars($value) ?></unitid>
<?php endif; ?>
<?php foreach ($descendant->getDates() as $date): ?>
        <unitdate <?php if ($type = $date->getType()->__toString()): ?><?php echo 'datechar="'.strtolower($type).'" ' ?><?php endif; ?><?php if ($startdate = $date->getStartDate()): ?><?php echo 'normal="'?><?php echo Qubit::renderDate($startdate) ?><?php if (0 < strlen($enddate = $date->getEndDate())): ?><?php echo '/'?><?php echo Qubit::renderDate($enddate) ?><?php endif; ?><?php echo '"' ?><?php endif; ?> encodinganalog="3.1.3"><?php echo esc_specialchars(Qubit::renderDateStartEnd($date->getDate(array('cultureFallback' => true)), $date->startDate, $date->endDate)) ?></unitdate>
<?php endforeach; ?>
<?php if (0 < count($creators = $descendant->getCreators())): ?>
        <origination encodinganalog="3.2.1">
<?php foreach ($creators as $creator): ?>
<?php if ($type = $creator->getEntityTypeId()): ?>
<?php if ($type == QubitTerm::PERSON_ID): ?>
          <persname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></persname>
<?php endif; ?>
<?php if ($type == QubitTerm::FAMILY_ID): ?>
          <famname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></famname>
<?php endif; ?>
<?php if ($type == QubitTerm::CORPORATE_BODY_ID): ?>
          <corpname><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></corpname>
<?php endif; ?>
<?php else: ?>
          <name><?php echo esc_specialchars($creator->getAuthorizedFormOfName(array('cultureFallback' => true))) ?></name>
<?php endif; ?>
<?php endforeach; ?>
        </origination>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getExtentAndMedium(array('cultureFallback' => true)))): ?>
        <physdesc><extent encodinganalog="3.1.5"><?php echo esc_specialchars($value) ?></extent></physdesc>
<?php endif; ?>
<?php if ($value = $descendant->getRepository()): ?>
        <repository>
          <corpname><?php echo esc_specialchars($value->__toString()) ?></corpname>
<?php if ($address = $value->getPrimaryContact()): ?>
      <address>
<?php if (0 < strlen($addressline = $address->getStreetAddress())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getCity())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getRegion())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $resource->getRepositoryCountry())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getPostalCode())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getTelephone())): ?>
          <addressline><?php echo __('Telephone: ').esc_specialchars($addressline) ?></addressline><?php endif; ?>
<?php if (0 < strlen($addressline = $address->getFax())): ?>
          <addressline><?php echo __('Fax: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getEmail())): ?>
          <addressline><?php echo __('Email: ').esc_specialchars($addressline) ?></addressline>
<?php endif; ?>
<?php if (0 < strlen($addressline = $address->getWebsite())): ?>
          <addressline><?php echo esc_specialchars($addressline) ?></addressline><?php endif; ?>
      </address>
<?php endif; ?>
      </repository>
<?php endif; ?>
<?php if (0 < count($langmaterial = $descendant->language)): ?>
    <langmaterial encodinganalog="3.4.3">
<?php foreach ($langmaterial as $languageCode): ?>
      <language langcode="<?php echo ($iso6392 = $iso639convertor->getID3($languageCode)) ? strtolower($iso6392) : $languageCode ?>"><?php echo format_language($languageCode) ?></language><?php endforeach; ?>
    </langmaterial><?php endif; ?>
<?php if (0 < count($notes = $descendant->getNotesByType(array('noteTypeId' => QubitTerm::GENERAL_NOTE_ID)))): ?>
<?php foreach ($notes as $note): ?><note type="<?php echo esc_specialchars($note->getType(array('cultureFallback' => true))) ?>" encodinganalog="3.6.1"><p><?php echo esc_specialchars($note->getContent(array('cultureFallback' => true))) ?></p></note>
<?php endforeach; ?>
<?php endif; ?>
      </did>
<?php foreach ($descendant->getCreators() as $creator): ?>
<?php if ($value = $creator->getHistory(array('cultureFallback' => true))): ?>
      <bioghist encodinganalog="3.2.2"><p><?php echo esc_specialchars($value) ?></p></bioghist>
<?php endif; ?>
<?php endforeach; ?>
<?php if (0 < strlen($value = $descendant->getScopeAndContent(array('cultureFallback' => true)))): ?>
      <scopecontent encodinganalog="3.3.1"><p><?php echo esc_specialchars($value) ?></p></scopecontent><?php endif; ?>
<?php if (0 < strlen($value = $descendant->getArrangement(array('cultureFallback' => true)))): ?>
      <arrangement encodinganalog="3.3.4"><p><?php echo esc_specialchars($value) ?></p></arrangement><?php endif; ?>
<?php if ((0 < count($materialtypes = $descendant->getMaterialTypes())) ||
            (0 < count($subjects = $descendant->getSubjectAccessPoints())) ||
            (0 < count($places = $descendant->getPlaceAccessPoints())) ||
            (0 < count($descendant->getActors()))): ?>
      <controlaccess>
<?php foreach ($descendant->getActorEvents() as $event): ?>
<?php if ($event->getActor()->getEntityTypeId() == QubitTerm::PERSON_ID): ?>
        <persname role="<?php echo $event->getType()->getRole() ?>"><?php echo esc_specialchars(render_title($event->getActor(array('cultureFallback' => true)))) ?> </persname>
<?php elseif ($event->getActor()->getEntityTypeId() == QubitTerm::FAMILY_ID): ?>
        <famname role="<?php echo $event->getType()->getRole() ?>"><?php echo esc_specialchars(render_title($event->getActor(array('cultureFallback' => true)))) ?> </famname>
<?php else: ?>
        <corpname role="<?php echo $event->getType()->getRole() ?>"><?php echo esc_specialchars(render_title($event->getActor(array('cultureFallback' => true)))) ?> </corpname>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($materialtypes as $materialtype): ?>
        <genreform><?php echo esc_specialchars($materialtype->getTerm()) ?></genreform>
<?php endforeach; ?>
<?php foreach ($subjects as $subject): ?>
        <subject><?php echo esc_specialchars($subject->getTerm()) ?></subject>
<?php endforeach; ?>
<?php foreach ($places as $place): ?>
        <geogname><?php echo esc_specialchars($place->getTerm()) ?></geogname>
<?php endforeach; ?>
      </controlaccess>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getPhysicalCharacteristics(array('cultureFallback' => true)))): ?>
      <phystech encodinganalog="3.4.4"><p><?php echo esc_specialchars($value) ?></p></phystech>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getAppraisal(array('cultureFallback' => true)))): ?>
      <appraisal encodinganalog="3.3.2"><p><?php echo esc_specialchars($value) ?></p></appraisal>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getAcquisition(array('cultureFallback' => true)))): ?>
      <acqinfo encodinganalog="3.2.4"><p><?php echo esc_specialchars($value) ?></p></acqinfo>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getAccruals(array('cultureFallback' => true)))): ?>
      <accruals encodinganalog="3.3.3"><p><?php echo esc_specialchars($value) ?></p></accruals>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getArchivalHistory(array('cultureFallback' => true)))): ?>
      <custodhist encodinganalog="3.2.3"><p><?php echo esc_specialchars($value) ?></p></custodhist>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getRevisionHistory(array('cultureFallback' => true)))): ?>
    <processinfo><p><date><?php echo esc_specialchars($value) ?></date></p></processinfo>
<?php endif; ?>
<?php if (0 < count($archivistsNotes = $descendant->getNotesByType(array('noteTypeId' => QubitTerm::ARCHIVIST_NOTE_ID)))): ?><?php foreach ($archivistsNotes as $note): ?><processinfo><p><?php echo esc_specialchars($note) ?></p></processinfo><?php endforeach; ?>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getLocationOfOriginals(array('cultureFallback' => true)))): ?>
    <originalsloc encodinganalog="3.5.1"><p><?php echo esc_specialchars($value) ?></p></originalsloc>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getLocationOfCopies(array('cultureFallback' => true)))): ?>
    <altformavail encodinganalog="3.5.2"><p><?php echo esc_specialchars($value) ?></p></altformavail>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getRelatedUnitsOfDescription(array('cultureFallback' => true)))): ?>
    <relatedmaterial encodinganalog="3.5.3"><p><?php echo esc_specialchars($value) ?></p></relatedmaterial>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getAccessConditions(array('cultureFallback' => true)))): ?>
    <accessrestrict encodinganalog="3.4.1"><p><?php echo esc_specialchars($value) ?></p></accessrestrict>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getReproductionConditions(array('cultureFallback' => true)))): ?>
    <userestrict encodinganalog="3.4.2"><p><?php echo esc_specialchars($value)  ?></p></userestrict>
<?php endif; ?>
<?php if (0 < strlen($value = $descendant->getFindingAids(array('cultureFallback' => true)))): ?>
    <otherfindaid encodinganalog="3.4.5"><p><?php echo esc_specialchars($value) ?></p></otherfindaid>
<?php endif; ?>
<?php if (0 < count($publicationNotes = $descendant->getNotesByType(array('noteTypeId' => QubitTerm::PUBLICATION_NOTE_ID)))): ?><?php foreach ($publicationNotes as $note): ?><bibliography encodinganalog="3.5.4"><p><?php echo esc_specialchars($note) ?></p></bibliography><?php endforeach; ?>
<?php endif; ?>
<?php if ($descendant->getRgt() == $descendant->getLft() + 1): ?>
    </c>
<?php else: ?>
<?php array_push($nestedRgt, $descendant->getRgt()) ?>
<?php endif; ?>
<?php // close <c> tag when we reach end of child list ?>
<?php $rgt = $descendant->getRgt() ?>
<?php while (count($nestedRgt) > 0 && $rgt + 1 == $nestedRgt[count($nestedRgt) - 1]): ?>
<?php $rgt = array_pop($nestedRgt); ?>
    </c>
<?php endwhile; ?>
<?php endforeach; ?>
  </dsc>
</archdesc>
</ead>
