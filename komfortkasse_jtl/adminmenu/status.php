<?php
namespace Plugin\komfortkasse_jtl;

global $oPlugin;

require_once __DIR__ . '/../includes/controller.php';
require_once __DIR__ . '/../includes/kk-lib/Komfortkasse_Config.php';


$api_key = Komfortkasse_Config::getConfig(Komfortkasse_Config::apikey);
$accesscode = Komfortkasse_Config::getConfig(Komfortkasse_Config::accesscode);

if (empty($api_key) or empty($accesscode)) {
	$smarty->assign("unconfigured", true);
} else {
	$smarty->assign("unconfigured", false);
}

echo $smarty->fetch($oPlugin->getPaths()->getAdminPath() . "status/templates/status.tpl");
?>