<?php
/**
 * This is a frontend visible file to be hooked into 132
 */
if(isset($_REQUEST["kkapi"])) {
	require_once(PFAD_ROOT.PFAD_PLUGIN.$oPlugin->cVerzeichnis."/".PFAD_PLUGIN_VERSION.$oPlugin->nVersion . '/includes/controller.php');
	KK_Controller::getInstance()->process($oPlugin);
}