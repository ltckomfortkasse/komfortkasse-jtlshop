<?php
namespace Plugin\komfortkasse_jtl;
require_once "kk-lib/Komfortkasse_Data_Fascade.php";
class KK_Controller {
	/**
	 * A reference to an instance of this class.
	 * 
	 * @var KK_Controller
	 */
	private static $instance;
	
	/**
	 * holds a cleaned version of the request-data
	 *
	 * @var array
	 */
	private $request_array;
	
	/**
	 *
	 * @var Komfortkasse_Data_Fascade
	 */
	private $fascade;
	private $oPlugin;
	
	public function get_oPlugin() {
		return $this->oPlugin;
	}
	
	/**
	 * Returns an instance of this class.
	 */
	public static function getInstance() {
		if (null == self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	
	private function __construct() {
		
		
		$this->fascade = new Komfortkasse_Data_Fascade ();
		$this->init ();
	}
	
	public function getRequestParameter($key) {
		if (isset ( $this->request_array [$key] )) {
			return $this->request_array [$key];
		} else {
			return false;
		}
	}
	
	protected function init() {
		$allowed_keys = array (
				'action',
				'o', 's',
				'order_id',
				'test',
				'accesscode',
				'accesscode_hash',
				'apikey',
				'privateKey',
				'publicKey',
				'testSSLEnc',
				'mCryptSecretKey',
				'mCryptIV',
				'testMCryptEnc',
				'testBase64Enc' 
		);
		$filters = array (
				'order_id' => 'int',
				'publicKey' => 'despace',
				'o' => (($_REQUEST['action'] == 'updateorders') ? 'urldecode' : 'all')
		);
		
		$this->request_array = array ();
		foreach ( $allowed_keys as $k ) {
			if (isset ( $_REQUEST [$k] )) {
				$this->request_array [$k] = $this->filterData ( $_REQUEST [$k], $filters [$k] );
			}
		}
	}
	
	/**
	 * should filterout any illegal data, javascript, html, etc.
	 * 
	 * @param unknown $data        	
	 */
	private function filterData($data, $filter = 'all') {
		if (is_array($filter)) {
			foreach ($filter as $f) {
				$data = $this->filterData($data, $f);
			}
			return $data;
		} else {
			switch($filter) {
				case 'despace':
					$ret = str_replace(' ', '+', urldecode($data));
					return $ret;
				case 'urldecode':
					return urldecode($data);
				case 'raw':
					return $data;
				case 'all':
				default:
					return urldecode($data);
			}
		}		
	}
	
	public function process($oPlugin) {
		$this->oPlugin = $oPlugin;
		// the real processing switch
		$action = strtolower ( $this->getRequestParameter ( 'action' ) );
		$action .= 'Action';
		
		if (method_exists ( $this, $action )) {
			call_user_func ( array (
					$this,
					$action 
			) );
		} else {
			die ( "unkown action" );
		}
	}
	public function sendResponse($data) {
		echo $data;
		exit ();
	}
	
	/**
	 * Init.
	 *
	 * @return void
	 */
	public function initAction() {
		$this->sendResponse ( $this->fascade->init () );
	}
	
	/**
	 * Test.
	 *
	 * @return void
	 */
	public function testAction() {
		$this->sendResponse ( $this->fascade->test () );
	}
	
	/**
	 * Read orders.
	 *
	 * @return void
	 */
	public function readordersAction() {
		$this->sendResponse ( $this->fascade->readorders () );
	}
	
	/**
	 * Read refunds.
	 *
	 * @return void
	 */
	public function readrefundsAction() {
		$this->sendResponse ( $this->fascade->readrefunds () );
	}
	
	/**
	 * Update orders.
	 *
	 * @return void
	 */
	public function updateordersAction() {
		$this->sendResponse ( $this->fascade->updateorders () );
	}
	
	/**
	 * Update refunds.
	 *
	 * @return void
	 */
	public function updaterefundsAction() {
		$this->sendResponse ( $this->fascade->updaterefunds () );
	}
	
	/**
	 * Info.
	 *
	 * @return void
	 */
	public function infoAction() {
		$this->sendResponse ( $this->fascade->info () );
	}
	
	/**
	 * NotifyOrder.
	 *
	 * @return void
	 */
	public function notifyorder($oPlugin, $id) {
		$this->oPlugin = $oPlugin;
		$this->fascade->notifyorder($id);
	}
}