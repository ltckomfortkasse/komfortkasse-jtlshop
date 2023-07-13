<?php
namespace Plugin\komfortkasse_jtl;
require_once "Komfortkasse.php";
/**
 * Komfortkasse
 * Data_Fascade Class
 * @version 1.0.0-JTL
 */
class Komfortkasse_Data_Fascade {
	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		return Komfortkasse::init ();
	}
	
	/**
	 * Test.
	 *
	 * @return void
	 */
	public function test() {
		return Komfortkasse::test ();
	}
	
	/**
	 * Read orders.
	 *
	 * @return void
	 */
	public function readorders() {
		return Komfortkasse::readorders ();
	}
	
	/**
	 * Read refunds.
	 *
	 * @return void
	 */
	public function readrefunds() {
		return Komfortkasse::readrefunds ();
	}
	
	/**
	 * Update orders.
	 *
	 * @return void
	 */
	public function updateorders() {
		return Komfortkasse::updateorders ();
	}
	
	/**
	 * Update refunds.
	 *
	 * @return void
	 */
	public function updaterefunds() {
		return Komfortkasse::updaterefunds ();
	}
	
	/**
	 * Info.
	 *
	 * @return void
	 */
	public function info() {
		return Komfortkasse::info ();
	}
	
	/**
	 * Notify order.
	 *
	 * @return void
	 */
	public function notifyorder($id) {
		return Komfortkasse::notifyorder ( $id );
	}
	
	public function readinvoicepdf() {
		return Komfortkasse::readinvoicepdf ();
	}
}