<?php
/**
 * hooked into 2 (Bestellabschluss)
 */
require_once(PFAD_ROOT.PFAD_PLUGIN.$oPlugin->cVerzeichnis."/".PFAD_PLUGIN_VERSION.$oPlugin->nVersion . '/includes/controller.php');
$obj = Shop::DB()->select('tbestellung', 'kBestellung', intval($_SESSION['kBestellung']));
KK_Controller::getInstance()->notifyorder($oPlugin, $obj->cBestellNr);