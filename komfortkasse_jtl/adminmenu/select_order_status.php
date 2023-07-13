<?php
$i = 1;
$options = array();

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_OFFEN;
$option->cName = 'Offen';
$option->nSort = ++$i;
$options[]     = $option;

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_IN_BEARBEITUNG;
$option->cName = 'In Bearbeitung';
$option->nSort = ++$i;
$options[]     = $option;

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_BEZAHLT;
$option->cName = 'Bezahlt';
$option->nSort = ++$i;
$options[]     = $option;

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_STORNO;
$option->cName = 'Storno';
$option->nSort = ++$i;
$options[]     = $option;

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_VERSANDT;
$option->cName = 'Versandt';
$option->nSort = ++$i;
$options[]     = $option;

$option  = new stdClass();
$option->cWert = BESTELLUNG_STATUS_TEILVERSANDT;
$option->cName = 'Teilversandt';
$option->nSort = ++$i;
$options[]     = $option;

return $options;
