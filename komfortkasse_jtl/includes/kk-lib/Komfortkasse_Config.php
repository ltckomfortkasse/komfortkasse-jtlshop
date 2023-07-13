<?php
namespace Plugin\komfortkasse_jtl;

use stdClass;

/**
 * Komfortkasse
 * Config Class
 *
 * @version 1.0.0-JTL
 */
class Komfortkasse_Config
{

    const activate_export = 'export';

    const activate_update = 'update';

    const payment_methods = 'payment_methods';

    const status_open = 'status_open';

    const status_paid = 'status_paid';

    const status_cancelled = 'status_cancelled';

    const payment_methods_invoice = 'payment_methods_invoice';

    const status_open_invoice = 'status_open_invoice';

    const status_paid_invoice = 'status_paid_invoice';

    const status_cancelled_invoice = 'status_cancelled_invoice';

    const payment_methods_cod = 'payment_methods_cod';

    const status_open_cod = 'status_open_cod';

    const status_paid_cod = 'status_paid_cod';

    const status_cancelled_cod = 'status_cancelled_cod';

    const encryption = 'encryption';

    const accesscode = 'accesscode';

    const apikey = 'apikey';

    const publickey = 'publickey';

    const privatekey = 'privatekey';

    /**
     * Set Config.
     *
     * @param string $constantKey
     *            Constant Key
     * @param string $value
     *            Value
     *            
     * @return void
     */
    public static function setConfig($constantKey, $value)
    {
        $oPlugin = KK_Controller::getInstance()->get_oPlugin();
        if (! isset($oPlugin)) {
            global $oPlugin;
        }
        $id = $oPlugin->getID();

        $sql = "DELETE from tplugineinstellungen where cName like '{$constantKey}' AND kPlugin = {$id}";
        \JTL\Shop::Container()->getDB()->query($sql, 3);

        $row['kPlugin'] = $id;
        $row['cName'] = $constantKey;
        $row['cWert'] = $value;
        \JTL\Shop::Container()->getDB()->insert("tplugineinstellungen", (object) $row);

        $options = $oPlugin->getConfig()->getOptions();
        foreach ($options as $o) {
            if ($o->valueID == $constantKey) {
                $o->value = $value;
            }
        }

        /*
         * $found = false;
         * foreach (self::$configData as $i => $o) {
         * if ($o->cName == $constantKey) {
         * $found = true;
         * $o->cWert = $value;
         * self::$configData[$i] = $o;
         * break;
         * }
         * }
         * if (! $found) {
         * $o = new stdClass();
         * $o->cName = $constantKey;
         * $o->cWert = $value;
         * self::$configData[] = $o;
         * }
         */
    }

    // end setConfig()

    /**
     * Get Config.
     *
     *
     * @param string $constantKey
     *            Constant Key
     *            
     * @return mixed
     */
    public static function getConfig($constantKey, $order = null)
    {
        $oPlugin = KK_Controller::getInstance()->get_oPlugin();
        if (! isset($oPlugin)) {
            global $oPlugin;
        }
        $option = $oPlugin->getConfig()->getOption($constantKey);
        if (!isset($option))
            return null;
        $ret = $option->value;
        if (is_array($ret)) {
            return implode(',', $ret);
        } else {
            return $ret;
        }
    }

    // end getConfig()

    /**
     * Get Request Parameter.
     *
     * @param string $key
     *            Key
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
        return APPLICATION_VERSION;
    }

    // end getVersion()
    public static function output($s)
    {
        return $s;
    }
    
    public static function log($s)
    {
        // do nothing
    }
    
}//end class