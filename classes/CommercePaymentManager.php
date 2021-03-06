<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id$
//

class CommercePaymentManager {
	private $mPaymentNames = array();
	private $selected_module;
	private $mPaymentObjects;

	// class constructor
	function __construct($module = '') {
		global $payment, $gBitCustomer;

		$this->mPaymentNames = array();
		$this->mPaymentObjects = array();

		if (defined('MODULE_PAYMENT_INSTALLED') && zen_not_null(MODULE_PAYMENT_INSTALLED)) {
			$this->mPaymentNames = explode(';', MODULE_PAYMENT_INSTALLED);

			$include_modules = array();

			if ( (zen_not_null($module)) && (in_array($module . '.' . substr($_SERVER['SCRIPT_NAME'], (strrpos($_SERVER['SCRIPT_NAME'], '.')+1)), $this->mPaymentNames)) ) {
				$this->selected_module = $module;

				$include_modules[] = array('class' => $module, 'file' => $module . '.php');
			} else {
				reset($this->mPaymentNames);

				// Free Payment Only shows
				if (zen_get_configuration_key_value('MODULE_PAYMENT_FREECHARGER_STATUS') and ($gBitCustomer->mCart->show_total()==0 and $gBitCustomer->mCart->show_weight()==0)) {
					$this->selected_module = $module;
					if (file_exists(DIR_FS_CATALOG . DIR_WS_MODULES . '/payment/' . 'freecharger.php')) {
						$include_modules[] = array('class'=> 'freecharger', 'file' => 'freecharger.php');
					}
				} else {
					// All Other Payment Modules show
					while (list(, $value) = each($this->mPaymentNames)) {
						// double check that the module really exists before adding to the array
						if (file_exists(DIR_FS_CATALOG . DIR_WS_MODULES . '/payment/' . $value)) {
							$class = substr($value, 0, strrpos($value, '.'));
							// Don't show Free Payment Module
							if ($class !='freecharger') {
								$include_modules[] = array('class' => $class, 'file' => $value);
							}
						}
					}
				}
			}

			for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
				$langFile = zen_get_file_directory(DIR_WS_LANGUAGES . $gBitCustomer->getLanguage() . '/modules/payment/', $include_modules[$i]['file'], 'false');
				if( file_exists( $langFile ) ) {
					include_once( $langFile );
				}
				include_once(DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);

				$this->mPaymentObjects[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
			}

			// if there is only one payment method, select it as default because in
			// checkout_confirmation.php the $payment variable is being assigned the
			// $_POST['payment'] value which will be empty (no radio button selection possible)
			if ( (zen_count_payment_modules() == 1) && (!isset($_SESSION['payment']) || (isset($_SESSION['payment']) && !is_object($_SESSION['payment']))) ) {
				$_SESSION['payment'] = $include_modules[0]['class'];
			}

			if ( (zen_not_null($module)) && (in_array($module, $this->mPaymentNames)) && (isset($this->mPaymentObjects[$module]->form_action_url)) ) {
				$this->form_action_url = $this->mPaymentObjects[$module]->form_action_url;
			}
		}
	}


	function isModuleActive( $pModuleName ) {
		return in_array( $pModuleName, $this->mPaymentNames );
	}

	// class methods
	/* The following method is needed in the checkout_confirmation.php page
	 due to a chicken and egg problem with the payment class and order class.
	 The payment modules needs the order destination data for the dynamic status
	 feature, and the order class needs the payment module title.
	 The following method is a work-around to implementing the method in all
	 payment modules available which would break the modules in the contributions
	 section. This should be looked into again post 2.2.
	*/
	function update_status( $pPaymentParameters ) {
			if ( !empty( $this->mPaymentObjects[$this->selected_module] ) && is_object($this->mPaymentObjects[$this->selected_module])) {
				if (method_exists($this->mPaymentObjects[$this->selected_module], 'update_status')) {
					$this->mPaymentObjects[$this->selected_module]->update_status( $pPaymentParameters );
				}
			}
	}

	function javascript_validation() {
		$js = '<script language="javascript"	type="text/javascript"><!-- ' . "\n" .
					'function check_form() {' . "\n" .
					'	var error = 0;' . "\n" .
					'	var error_message = "' . JS_ERROR . '";' . "\n" .
					'	var payment_value = null;' . "\n" .
					'	if (document.checkout_payment.payment.length) {' . "\n" .
					'		for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
					'			if (document.checkout_payment.payment[i].checked) {' . "\n" .
					'				payment_value = document.checkout_payment.payment[i].value;' . "\n" .
					'			}' . "\n" .
					'		}' . "\n" .
					'	} else if (document.checkout_payment.payment.checked) {' . "\n" .
					'		payment_value = document.checkout_payment.payment.value;' . "\n" .
					'	} else if (document.checkout_payment.payment.value) {' . "\n" .
					'		payment_value = document.checkout_payment.payment.value;' . "\n" .
					'	}' . "\n\n";

		reset($this->mPaymentNames);
		while (list(, $value) = each($this->mPaymentNames)) {
			$class = substr($value, 0, strrpos($value, '.'));
			if ( !empty($this->mPaymentObjects[$class]) && $this->mPaymentObjects[$class]->enabled) {
				$js .= $this->mPaymentObjects[$class]->javascript_validation();
			}
		}

		$js .= "\n" . '	if (payment_value == null && submitter != 1) {' . "\n" .
					 '		error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
					 '		error = 1;' . "\n" .
					 '	}' . "\n\n" .
					 '	if (error == 1 && submitter != 1) {' . "\n" .
					 '		alert(error_message);' . "\n" .
					 '		return false;' . "\n" .
					 '	} else {' . "\n" .
					 '		return true;' . "\n" .
					 '	}' . "\n" .
					 '}' . "\n" .
					 '//--></script>' . "\n";

		return $js;
	}

	function selection() {
		$selection_array = array();

		if (is_array($this->mPaymentNames)) {
			reset($this->mPaymentNames);
			while (list(, $value) = each($this->mPaymentNames)) {
				$class = substr($value, 0, strrpos($value, '.'));
				if ( !empty( $this->mPaymentObjects[$class] ) && $this->mPaymentObjects[$class]->enabled) {
					$selection = $this->mPaymentObjects[$class]->selection();
					if (is_array($selection)) $selection_array[] = $selection;
				}
			}
		}

		return $selection_array;
	}

	function verifyPayment( &$pPaymentParameters, &$pOrder ) {
		$ret = FALSE;
		if( $pOrder->hasPaymentDue() ) {	
			if ( !empty( $this->mPaymentObjects[$this->selected_module] ) && is_object($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) ) {
				$ret = $this->mPaymentObjects[$this->selected_module]->verifyPayment( $pPaymentParameters, $pOrder );
			}
		} else {
			$ret = TRUE;
		}
		return $ret;
	}

	function confirmation( $pPaymentParameters = NULL ) {
		if ( !empty( $this->mPaymentObjects[$this->selected_module] ) && is_object($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) ) {
			return $this->mPaymentObjects[$this->selected_module]->confirmation( $pPaymentParameters );
		}
	}

	function process_button( $pPaymentParameters = NULL ) {
		if ( !empty( $this->mPaymentObjects[$this->selected_module] ) && is_object($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) ) {
			return $this->mPaymentObjects[$this->selected_module]->process_button( $pPaymentParameters );
		}
	}

	function processPayment( $pPaymentParameters, $pOrder ) {
		global $gBitProduct;
		$ret = NULL;
		$gBitProduct->invokeServices( 'commerce_pre_purchase_function', $pOrder );
		if( !empty( $this->mPaymentObjects[$this->selected_module] ) && !empty( $this->mPaymentObjects[$this->selected_module]->enabled ) ) {
			$ret = $this->mPaymentObjects[$this->selected_module]->processPayment( $pPaymentParameters, $pOrder );
		}
		return $ret;
	}

	function after_order_create($zf_order_id) {
		global $gBitUser, $gBitProduct, $gCommerceSystem, $order;
		$ret = NULL;
		if( round( $order->getField( 'total', 2 ) ) > 0 && ($groupId = $gCommerceSystem->getConfig( 'CUSTOMERS_PURCHASE_GROUP' )) ) {
			$gBitUser->addUserToGroup( $gBitUser->mUserId, $groupId );
		}
		$gBitProduct->invokeServices( 'commerce_post_purchase_function', $order );
		if (!empty($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) && (method_exists($this->mPaymentObjects[$this->selected_module], 'after_order_create'))) {
			return $this->mPaymentObjects[$this->selected_module]->after_order_create($zf_order_id);
		}
		return $ret;
	}

	function admin_notification($zf_order_id) {
		if (is_object($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) && (method_exists($this->mPaymentObjects[$this->selected_module], 'admin_notification'))) {
			return $this->mPaymentObjects[$this->selected_module]->admin_notification($zf_order_id);
		}
	}

	function get_error() {
		if (is_object($this->mPaymentObjects[$this->selected_module]) && ($this->mPaymentObjects[$this->selected_module]->enabled) ) {
			return $this->mPaymentObjects[$this->selected_module]->get_error();
		}
	}
}
?>
