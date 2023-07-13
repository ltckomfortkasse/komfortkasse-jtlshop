<?php
global $oPlugin;

require_once __DIR__ . '/../includes/controller.php';
require_once __DIR__ . '/../includes/kk-lib/Komfortkasse_Config.php';

function statusOptions($val) {
	if (!is_array($val)) {
		$val = explode(',', $val);
	}
	
	$o[BESTELLUNG_STATUS_OFFEN] = 'Offen';
	$o[BESTELLUNG_STATUS_IN_BEARBEITUNG] = 'In Bearbeitung';
	$o[BESTELLUNG_STATUS_BEZAHLT] = 'Bezahlt';
	$o[BESTELLUNG_STATUS_STORNO] = 'Storno';
	$o[BESTELLUNG_STATUS_VERSANDT] = 'Versandt';
	$o[BESTELLUNG_STATUS_TEILVERSANDT] = 'Teilversandt';
	
	$r = '';
	foreach ($o as $k => $display) {
		$r .= '<option value="' . $k . '" ' .
			(in_array($k, $val) ? ' selected ' : '')
			. '>' . $display . '</option>';
	}
	return $r;
}

function pmOptions($val) {
	if (!is_array($val)) {
		$val = explode(',', $val);
	}
	$rows = Shop::DB()->query("select kZahlungsart as val, cName as display from tzahlungsart where nActive=1 order by kZahlungsart", 2);
	$r = '';
	foreach ($rows as $row) {
		$k = $row->val;
		$display = $row->display;
		
		$r .= '<option value="' . $k . '" ' .
				(in_array($k, $val) ? ' selected ' : '')
				. '>' . $display . '</option>';
	}
	return $r;
}

$op['export'] = Komfortkasse_Config::activate_export;
$op['update'] = Komfortkasse_Config::activate_update;

$op['status_open'] = Komfortkasse_Config::status_open;
$op['status_paid'] = Komfortkasse_Config::status_paid;
$op['status_cancelled'] = Komfortkasse_Config::status_cancelled;
$op['payment_methods'] = Komfortkasse_Config::payment_methods;

$op['status_open_cod'] = Komfortkasse_Config::status_open_cod;
$op['status_paid_cod'] = Komfortkasse_Config::status_paid_cod;
$op['status_cancelled_cod'] = Komfortkasse_Config::status_cancelled_cod;
$op['payment_methods_cod'] = Komfortkasse_Config::payment_methods_cod;

$op['status_open_invoice'] = Komfortkasse_Config::status_open_invoice;
$op['status_paid_invoice'] = Komfortkasse_Config::status_paid_invoice;
$op['status_cancelled_invoice'] = Komfortkasse_Config::status_cancelled_invoice;
$op['payment_methods_invoice'] = Komfortkasse_Config::payment_methods_invoice;

if (isset ( $_POST["set"] ) && ! empty ( $_POST ["set"] )) {
	foreach ( $_POST["set"] as $cKey => $cValue ) {
		if (is_array($cValue)) {
			$cValue = implode(',', $cValue);
		}
		Komfortkasse_Config::setConfig($op[$cKey], $cValue);
	}
	
	if(!isset($_POST["set"]['update'])) {
		Komfortkasse_Config::setConfig($op['update'], '0');
	}
	if(!isset($_POST["set"]['export'])) {
		Komfortkasse_Config::setConfig($op['export'], '0');
	}
}

$api_key = Komfortkasse_Config::getConfig(Komfortkasse_Config::apikey);
$accesscode = Komfortkasse_Config::getConfig(Komfortkasse_Config::accesscode);

if (empty($api_key) or empty($accesscode)) {
	$smarty->assign("unconfigured", true);
} else {
	$smarty->assign("unconfigured", false);
}
$smarty->assign("status_open_options", statusOptions(Komfortkasse_Config::getConfig($op['status_open'])));
$smarty->assign("status_paid_options", statusOptions(Komfortkasse_Config::getConfig($op['status_paid'])));
$smarty->assign("status_cancelled_options", statusOptions(Komfortkasse_Config::getConfig($op['status_cancelled'])));

$smarty->assign("status_open_cod_options", statusOptions(Komfortkasse_Config::getConfig($op['status_open_cod'])));
$smarty->assign("status_paid_cod_options", statusOptions(Komfortkasse_Config::getConfig($op['status_paid_cod'])));
$smarty->assign("status_cancelled_cod_options", statusOptions(Komfortkasse_Config::getConfig($op['status_cancelled_cod'])));

$smarty->assign("status_open_invoice_options", statusOptions(Komfortkasse_Config::getConfig($op['status_open_invoice'])));
$smarty->assign("status_paid_invoice_options", statusOptions(Komfortkasse_Config::getConfig($op['status_paid_invoice'])));
$smarty->assign("status_cancelled_invoice_options", statusOptions(Komfortkasse_Config::getConfig($op['status_cancelled_invoice'])));

$smarty->assign("payment_methods_options", pmOptions(Komfortkasse_Config::getConfig($op['payment_methods'])));
$smarty->assign("payment_methods_cod_options", pmOptions(Komfortkasse_Config::getConfig($op['payment_methods_cod'])));
$smarty->assign("payment_methods_invoice_options", pmOptions(Komfortkasse_Config::getConfig($op['payment_methods_invoice'])));

$exp = Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_export);
if($exp) {
	$smarty->assign("export_checked", "checked");
} else {
	$smarty->assign("export_checked", "");
}
if(Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_update)) {
	$smarty->assign("update_checked", "checked");
} else {
	$smarty->assign("update_checked", "");
}
echo $smarty->fetch($oPlugin->cAdminmenuPfad . "settings/templates/settings.tpl");
?>