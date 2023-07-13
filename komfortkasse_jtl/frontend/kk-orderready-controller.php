<?php
/**
 * hooked into 2 (Bestellabschluss)
 */
use Plugin\komfortkasse_jtl\KK_Controller;

require_once($oPlugin->getPaths()->getBasePath().'includes/controller.php');
$obj = \JTL\Shop::Container()->getDB()->select('tbestellung', 'kBestellung', intval($_SESSION['kBestellung']));
KK_Controller::getInstance()->notifyorder($oPlugin, $obj->cBestellNr);