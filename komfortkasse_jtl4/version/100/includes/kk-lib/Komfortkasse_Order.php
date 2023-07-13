<?php
/**
 * Komfortkasse Order Class
 * in KK, an Order is an Array providing the following members:
 * number, date, email, customer_number, payment_method, amount, currency_code, exchange_rate, language_code, invoice_number, store_id
 * status: data type according to the shop system
 * delivery_ and billing_: _firstname, _lastname, _company, _street, _postcode, _city, _countrycode
 * products: an Array of item numbers
 * @version 1.0.0-JTL
 */
class Komfortkasse_Order {
	private static function createInClause($arr) {
		if (! is_array ( $arr )) {
			$arr = explode ( ',', $arr );
		}
		$tmp = array ();
		foreach ( $arr as $item ) {
			$tmp [] = str_replace ( '\'', '', $item );
		}
		return '(\'' . implode ( '\', \'', $tmp ) . '\')';
	}

	/**
	 * Get open order IDs.
	 * @param string $type cBestellNr or kBestellung
	 * @return string all order IDs that are "open" and relevant for transfer to kk
	 */
	public static function getOpenIDs($type = 'cBestellNr') {
		$ret = array ();

		$minDate = date ( 'Y-m-d', time () - 31536000 ); // 1 Jahr

		$shopIds = Komfortkasse_Config::getRequestParameter ( 's' );
		if ($shopIds)
			$shopIds = Komfortkasse::kkdecrypt ( $shopIds );

		$openOrders = array();
		$openOrders = array_merge($openOrders, explode(",", Komfortkasse_Config::getConfig ( Komfortkasse_Config::status_open)));
		$openOrders = array_merge($openOrders, explode(",", Komfortkasse_Config::getConfig ( Komfortkasse_Config::status_open_invoice)));
		$openOrders = array_merge($openOrders, explode(",", Komfortkasse_Config::getConfig ( Komfortkasse_Config::status_open_cod)));

		if (true || Komfortkasse_Config::getConfig ( Komfortkasse_Config::activate_export )) {
			$query  = "select ";
			$query .= "b.kBestellung, b.cBestellNr ";
			$query .= ", b.kZahlungsart, b.cZahlungsartName, b.cStatus, b.dErstellt, b.dBezahltDatum ";
			$query .= "from tbestellung b ";

			$where = array();

			$where[] = "b.dErstellt > {$minDate}";

			$paymentMethods_prepaid 	= Komfortkasse_Config::getConfig ( Komfortkasse_Config::payment_methods);
			$paymentMethods_cod 		= Komfortkasse_Config::getConfig ( Komfortkasse_Config::payment_methods_cod);
			$paymentMethods_invoice 	= Komfortkasse_Config::getConfig ( Komfortkasse_Config::payment_methods_invoice);

			$where_or = array();
			if(!empty($paymentMethods_prepaid)) {
				$where_or[] = "b.kZahlungsart in " . self::createInClause ( $paymentMethods_prepaid );
			}
			if(!empty($paymentMethods_cod)) {
				$where_or[] = "b.kZahlungsart in " . self::createInClause ( $paymentMethods_cod );
			}
			if(!empty($paymentMethods_invoice)) {
				$where_or[] = "b.kZahlungsart in " . self::createInClause ( $paymentMethods_invoice );
			}
			$where[] = "(" . implode(") or (", $where_or) . ")";

			$where[] = "b.cStatus in " . self::createInClause ( $openOrders );

			$query .= "where (" . implode(") and (", $where) . ")";

			$rows = Shop::DB()->query($query, 2);

			foreach ( $rows as $result ) {
				$ret[] = $result->{$type};
			}
		}
		return $ret;
	}

	// end getOpenIDs()

	/**
	 * Get refund IDS.
	 *
	 * @return string all refund IDs that are "open" and relevant for transfer to kk
	 */
	public static function getRefundIDs() {
		return null;
	}

	// end getRefundIDs()

	/**
	 * Get order.
	 *
	 * @param string $number
	 *        	order number
	 *
	 * @return array order
	 */
	public static function getOrder($number, $type = 'cBestellNr') {
		if ($type == 'cBestellNr') {
			$obj = Shop::DB()->select('tbestellung', 'cBestellNr', $number);
			$number = $obj->kBestellung;
		}
		$oBestellung = new Bestellung ( $number );
		$oBestellung->fuelleBestellung ( 1, 0, false );

		if (empty ($oBestellung->kBestellung)) {
			return null;
		}

		$oSprache = Shop::DB()->select('tsprache', 'ksprache', $oBestellung->kSprache);

		$ret = array ();
		$ret ['store_id'] = 0;
		$ret ['invoice_date'] = null;

		$ret ['number'] = $oBestellung->cBestellNr;
		$ret ['status'] = $oBestellung->cStatus;
		$ret ['date'] = date ( 'd.m.Y', strtotime ( $oBestellung->dErstelldatum_en ) );
		$ret ['email'] = utf8_encode ($oBestellung->oKunde->cMail);
		$ret ['customer_number'] = $oBestellung->oKunde->cKundenNr;
		try {
			$ret ['payment_method'] = $oBestellung->kZahlungsart;
			$ret ['payment_name'] = utf8_encode ($oBestellung->cZahlungsartName);
		} catch ( Exception $e ) {
		}
		$ret ['amount'] = $oBestellung->fGesamtsumme;
		$ret ['currency_code'] = $oBestellung->Waehrung->cName;
		$ret ['exchange_rate'] = $oBestellung->Waehrung->fFaktor;

		$shippingAddress = $oBestellung->Lieferadresse;
		if ($shippingAddress) {
			$ret ['delivery_firstname'] = utf8_encode ( $shippingAddress->cVorname );
			$ret ['delivery_lastname'] = utf8_encode ( $shippingAddress->cNachname );
			$ret ['delivery_company'] = utf8_encode ( $shippingAddress->cFirma );
			$ret ['delivery_street'] = utf8_encode ( $shippingAddress->cStrasse . ' ' . $shippingAddress->cHausnummer );
			$ret ['delivery_postcode'] = utf8_encode ( $shippingAddress->cPLZ );
			$ret ['delivery_city'] = utf8_encode ( $shippingAddress->cOrt );
			$ret ['delivery_countrycode'] = utf8_encode ( $shippingAddress->cLand );
		}

		$billingAddress = $oBestellung->oRechnungsadresse;
		if ($billingAddress) {
			// de-DE
			$ret ['language_code'] = strtolower($oSprache->cISO) . "-" . strtoupper($oBestellung->oRechnungsadresse->cLand);
			$ret ['billing_firstname'] = utf8_encode ( $billingAddress->cVorname );
			$ret ['billing_lastname'] = utf8_encode ( $billingAddress->cNachname );
			$ret ['billing_company'] = utf8_encode ( $billingAddress->cFirma );
			$ret ['billing_street'] = utf8_encode ( $billingAddress->cStrasse . ' ' . $billingAddress->cHausnummer );
			$ret ['billing_postcode'] = utf8_encode ( $billingAddress->cPLZ );
			$ret ['billing_city'] = utf8_encode ( $billingAddress->cOrt );
			$ret ['billing_countrycode'] = utf8_encode ( $billingAddress->cLand );
		} else {
			$ret ['language_code'] = strtolower($oSprache->cISO) . "-" . strtoupper($oBestellung->oKunde->cLand);
		}

		foreach ( $oBestellung->Positionen as $item ) {
			if ($item->kArtikel > 0) {
				$art = new Artikel();
				$art->fuelleArtikel($item->kArtikel, null);

				if (!empty($art->cArtNr)) {
					$ret ['products'][] = $art->cArtNr;
				} else {
					$ret ['products'][] = $art->cName;
				}
			}
		}
		return $ret;
	}

	// end getOrder()

	/**
	 * Update order.
	 *
	 * @param array $order
	 *        	order
	 * @param string $status
	 *        	status
	 * @param string $callbackid
	 *        	callback ID
	 *
	 * @return void
	 */
	public static function updateOrder($order, $status, $callbackid) {
		if (! Komfortkasse_Config::getConfig ( Komfortkasse_Config::activate_update, $order )) {
			return;
		}

		$obj = Shop::DB()->select('tbestellung', 'cBestellNr', $order['number']);
		$oBestellung = new Bestellung ( $obj->kBestellung );
		$oBestellung->fuelleBestellung ( 1, 0, false );

		if ($status == BESTELLUNG_STATUS_BEZAHLT) {
			// write the new status into the DB
			$oBestellung->cStatus = $status;
			$oBestellung->dBezahltDatum = 'now()';
			$oBestellung->cAbgeholt = 'N';
			$oBestellung->updateInDB();

			// and this is the incoming transaction
			$zahlungseingang                    = new stdClass();
			$zahlungseingang->kBestellung       = $oBestellung->kBestellung;
			$zahlungseingang->cZahlungsanbieter = 'Komfortkasse';
			$zahlungseingang->fBetrag           = $oBestellung->fGesamtsumme;
			$zahlungseingang->cISO              = $oBestellung->Waehrung->cName;
			$zahlungseingang->cAbgeholt         = 'N';
			$zahlungseingang->dZeit             = date('Y-m-d H:m:s');
			// this is the tx-id
			$zahlungseingang->cHinweis         = $callbackid;
			Shop::DB()->insert('tzahlungseingang', $zahlungseingang);
		} elseif ($status == BESTELLUNG_STATUS_STORNO) {
			$oBestellung->cStatus = $status;
			$oBestellung->cAbgeholt = 'N';
			$oBestellung->updateInDB();
		}
	}

	// end updateOrder()

	public static function isOpen($order) {
		global $komfortkasse_order_extension;
		if ($komfortkasse_order_extension && method_exists ( 'Komfortkasse_Order_Extension', 'isOpen' ) === true) {
			$ret = Komfortkasse_Order_Extension::isOpen ( $order );
		} else {
			$ret = true;
		}

		return $ret;
	}

	/**
	 * Update refunds.
	 *
	 * @param string $refundIncrementId
	 *        	Increment ID of refund
	 * @param string $status
	 *        	status
	 * @param string $callbackid
	 *        	callback ID
	 *
	 * @return void
	 */
	public static function updateRefund($refundIncrementId, $status, $callbackid) {
		return null;
	}

	public static function getInvoicePdfPrepare() {
		return null;
	}

	public static function getInvoicePdf($invoiceNumber, $orderNumber) {
		return null;
	}

	/**
	 * Get refund.
	 *
	 * @param string $number refund number
	 *
	 * @return array refund
	 */
	public static function getRefund($number) {
		return null;
	}

	// end getRefund()
}//end class