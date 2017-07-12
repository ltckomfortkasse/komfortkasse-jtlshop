<?php
/**
 * Komfortkasse
 * Config Class
 * @version 1.0.0-JTL 
 */
class Komfortkasse_Config
{
	/**
	 * Just an array of config data. Once loaded we will also "dechunk" the data
	 * @see getConfig
	 * 
	 * @var array $configData
	 */
	static public $configData;
	
    const activate_export = 'payment/komfortkasse/activate_export';
    const activate_update = 'payment/komfortkasse/activate_update';
    
    const payment_methods = 'payment/komfortkasse/payment_methods';
    const status_open = 'payment/komfortkasse/status_open';
    const status_paid = 'payment/komfortkasse/status_paid';
    const status_cancelled = 'payment/komfortkasse/status_cancelled';
    
    const payment_methods_invoice = 'payment/komfortkasse/payment_methods_invoice';
    const status_open_invoice = 'payment/komfortkasse/status_open_invoice';
    const status_paid_invoice = 'payment/komfortkasse/status_paid_invoice';
    const status_cancelled_invoice = 'payment/komfortkasse/status_cancelled_invoice';
    
    const payment_methods_cod = 'payment/komfortkasse/payment_methods_cod';
    const status_open_cod = 'payment/komfortkasse/status_open_cod';
    const status_paid_cod = 'payment/komfortkasse/status_paid_cod';
    const status_cancelled_cod = 'payment/komfortkasse/status_cancelled_cod';
    
    const encryption = 'payment/komfortkasse/encryption';
    const accesscode = 'payment/komfortkasse/accesscode';
    const apikey = 'payment/komfortkasse/apikey';
    const publickey = 'payment/komfortkasse/publickey';
    const privatekey = 'payment/komfortkasse/privatekey';
    const use_invoice_total = 'payment/komfortkasse/use_invoice_total';
    const consider_creditnotes = 'payment/komfortkasse/consider_creditnotes';
    const creditnotes_as_invoices = 'payment/komfortkasse/creditnotes_as_invoices';
    const last_receipt_only = 'payment/komfortkasse/last_receipt_only';
    const set_invoices_paid = 'payment/komfortkasse/set_invoices_paid';

    /**
     * Set Config.
     *
     * @param string $constantKey Constant Key
     * @param string $value Value
     *
     * @return void
     */
    public static function setConfig($constantKey, $value)
    {
    	$plugin = KK_Controller::getInstance()->get_oPlugin();
    	if (isset($plugin)) {
    		$id = KK_Controller::getInstance()->get_oPlugin()->kPlugin;
    	} else {
    		global $oPlugin;
    		$id = $oPlugin->kPlugin;
    	}
    	$sql = "DELETE from tplugineinstellungen where cName like '{$constantKey}%' AND kPlugin = {$id}";
    	Shop::DB()->query($sql, 3);
    	
    	if (strlen($value) > 240) {
    		$row['kPlugin'] = $id;
    		$row['cName'] = $constantKey;
    		$row['cWert'] = '__CHUNKED__';
    		Shop::DB()->insert("tplugineinstellungen", (object)$row);
    		
    		$arr = explode("\t\t", chunk_split($value, 240, "\t\t"));
    		foreach ($arr as $k => $v) {
    			if (!empty($v)) {
	    			$row = array();
	    			$row['kPlugin'] = $id;
	    			$row['cName'] = $constantKey . "__CHUNK__" . $k;
	    			$row['cWert'] = $v;
	    			Shop::DB()->insert("tplugineinstellungen", (object)$row);
    			}
    		}
    	} else {
    		$row['kPlugin'] = $id;
    		$row['cName'] = $constantKey;
    		$row['cWert'] = $value;
    		Shop::DB()->insert("tplugineinstellungen", (object)$row);
    	}
    	
    	$found = false;
    	foreach (self::$configData as $i => $o) {
    		if ($o->cName == $constantKey) {
    			$found = true;
    			$o->cWert = $value;
    			self::$configData[$i] = $o;
    			break;
    		}
    	}
    	if (!$found) {
    		$o = new stdClass;
    		$o->cName = $constantKey;
    		$o->cWert = $value;
    		self::$configData[] = $o;
    	}
    }
 	// end setConfig()

    private static function initDefaults() {
    	self::setConfig ( self::activate_export, 1);
    	self::setConfig ( self::activate_update, 1);
    
    	// presets status
    	$openDef1 = BESTELLUNG_STATUS_OFFEN . "," . BESTELLUNG_STATUS_IN_BEARBEITUNG;
    	$openDef2 = BESTELLUNG_STATUS_VERSANDT;
    	 
    	self::setConfig ( self::status_open, $openDef1);
    	self::setConfig ( self::status_paid, BESTELLUNG_STATUS_BEZAHLT);
    	self::setConfig ( self::status_cancelled, BESTELLUNG_STATUS_STORNO);
    
    	self::setConfig ( self::status_open_invoice, $openDef2);
    	self::setConfig ( self::status_paid_invoice, BESTELLUNG_STATUS_BEZAHLT);
    	self::setConfig ( self::status_cancelled_invoice, BESTELLUNG_STATUS_STORNO);
    
    	self::setConfig ( self::status_open_cod, $openDef2);
    	self::setConfig ( self::status_paid_cod, BESTELLUNG_STATUS_BEZAHLT);
    	self::setConfig ( self::status_cancelled_cod, BESTELLUNG_STATUS_STORNO);
    
    	self::setConfig ( self::payment_methods, "1");
    	self::setConfig ( self::payment_methods_cod, "2");
    	self::setConfig ( self::payment_methods_invoice, "3");
    }
    
    /**
     * Get Config.
     *
     *
     * @param string $constantKey Constant Key
     *
     * @return mixed
     */
    public static function getConfig($constantKey, $order=null)
    {
    	if (self::$configData == null) {
    		// read the config data
    		$oPlugin = KK_Controller::getInstance()->get_oPlugin();
    		if (isset($oPlugin)) {
    			$arr_raw = $oPlugin->oPluginEinstellung_arr;
    		} else {
    			global $oPlugin;
    			$arr_raw = $oPlugin->oPluginEinstellung_arr;
    		}   
    		if (count($arr_raw) == 0) {
    			self::initDefaults();
    			$arr_raw = self::$configData;
    		}
    		
    		$arr_names = array();
    		foreach ($arr_raw as $k => $o) {
    			$arr_names[$o->cName] = $k;
    		}

    		foreach ($arr_names as $name => $key) {
    			$parts = explode("__CHUNK__", $name);
    			if (count($parts) < 2) {
    				$o = $arr_raw[$key];
    				if ($o->cWert == '__CHUNKED__') {
    					$i = 0;
						$v = "";
						while(isset($arr_names[$name.'__CHUNK__'.$i])) {
							$k = $arr_names[$name.'__CHUNK__'.$i];
							$v .= $arr_raw[$k]->cWert;
							unset($arr_raw[$k]);
							$i++;
						}
						$arr_raw[$key]->cWert = $v; 
    				} 
    			} 
    		}
    		self::$configData = KK_Controller::getInstance()->get_oPlugin()->oPluginEinstellung_arr = $arr_raw;
    	} 
    	
        foreach (self::$configData as $o) {
        	if ($o->cName == $constantKey) {
        		return $o->cWert;
        	}
        }
        
        return "";
    }
 	// end getConfig()


    /**
     * Get Request Parameter.
     *
     * @param string $key Key
     *
     * @return string
     */
    public static function getRequestParameter($key)
    {
    	return KK_Controller::getInstance()->getRequestParameter($key);
    }
 	// end getRequestParameter()

    /**
     * Get JTL Version.
     *
     * @return string
     */
    public static function getVersion()
    {
		return JTL_VERSION;
    } // end getVersion()

    public static function output($s)
    {
        return $s;
    }

}//end class