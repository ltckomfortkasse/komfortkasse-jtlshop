<?php
/**
 * This is a frontend visible file to be hooked into 132
 */
use Plugin\komfortkasse_jtl\KK_Controller;

if(isset($_REQUEST["kkapi"])) {
    require_once($oPlugin->getPaths()->getBasePath().'includes/controller.php');
	KK_Controller::getInstance()->process($oPlugin);
}