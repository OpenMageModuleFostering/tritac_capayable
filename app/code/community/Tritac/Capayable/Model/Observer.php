<?php
/**
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Model_Observer
{
    
    public function processInvoice(Varien_Event_Observer $observer)
    {
    	Mage::log("processInvoice");
        $event = $observer->getEvent();
        /** @var $_shipment Mage_Sales_Model_Order_Shipment */
        $_shipment = $event->getShipment();
        /** @var $_order Mage_Sales_Model_Order */
        $_order = $_shipment->getOrder();
        /** @var $payment */
        $payment = $_order->getPayment();
        /** @var $paymentInstance */
        $paymentInstance = $payment->getMethodInstance();

        /**
         * Check if user choose Capayable payment method
         */
        if($paymentInstance->getCode() == 'capayable') {

            $_order->setActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_INVOICE, true);
            $customerEmail = $_order->getCustomerEmail();
            $_capayableCustomer = Mage::getModel('capayable/customer')->loadByEmail($customerEmail);

            /**
             * Customer credit check
             */
            $amount = Mage::helper('capayable')->convertToCents($_order->getGrandTotal());
            $apiResult = $paymentInstance->checkCredit($_capayableCustomer, $amount, true);

            if(!$apiResult->getIsAccepted() || !$apiResult->getTransactionNumber()) {
                return false;
            }

            /**
             * Prepare shipped items that need to be added to invoice
             */
            foreach ($_shipment->getItemsCollection() as $item) {
                $qtys[$item->getOrderItemId()] = $item->getQty();
            }

            try {
                // Initialize new magento invoice
                $invoice = Mage::getModel('sales/service_order', $_order)->prepareInvoice($qtys);
                // Throw exception if invoice don't have any items
                if(!$invoice->getTotalQty()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an empty invoice.'));
                }
                // Set magento transaction id which returned from capayable
                $payment->setTransactionId($apiResult->getTransactionNumber());
                // Allow payment capture and register new magento transaction
                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                // Register invoice and apply it to order, order items etc.
                $invoice->register();
                $invoice->setEmailSent(true);
                $invoice->getOrder()->setIsInProcess(true);

                $transaction = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                // Commit changes or rollback if error has occurred
                $transaction->save();

                /**
                 * Register invoice with Capayable
                 */
                
                $isApiInvoiceAccepted = $paymentInstance->processApiInvoice($invoice);

                if($isApiInvoiceAccepted) {
			$coreConfig = new Mage_Core_Model_Config();
			$old_copy_to = Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_TO);
			$old_copy_method = Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_METHOD);
			$coreConfig->saveConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_TO, "capayable-invoice-bcc@tritac.com");
			$coreConfig->saveConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_METHOD, "bcc");
			
			Mage::getConfig()->reinit();
			Mage::app()->reinitStores();
			
			$invoice_number = $invoice->getIncrementId();			
			Mage::register('invoice_number', $invoice_number);
			
			// Send email notification about registered invoice
			$invoice->sendEmail(true);
			
			/*	
			if ($_order->getCustomerIsGuest()) {
				$customer_name = $_order->getBillingAddress()->getName();
			} else {
				$customer_name = $_order->getCustomerName();
			}
			
			$customer_email = $_order->getCustomerEmail();
			
			$sender_email = Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_IDENTITY);
			$invoice_number = $invoice->getIncrementId();
			
			$mail = Mage::getModel('core/email');
			$mail->setToName($customer_name);
			$mail->setToEmail($customer_email);
			$mail->setBody("Payment Details<br>Account name: 'Stichting Beheer Gelden Capayable'<br>Account number: NL42INGB0006528043 <br>Payment reference: the shop's invoice number (InvoiceNumber):{$invoice_number}<br>");
			$mail->setSubject('Payment Details');
			$mail->setFromEmail($sender_email);
			$mail->setType('html');// YOu can use Html or text as Mail format

			$mail->send();
			*/
						
			$coreConfig->saveConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_TO, "{$old_copy_to}");
			$coreConfig->saveConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_COPY_METHOD, "{$old_copy_method}");
			
			Mage::getConfig()->reinit();
			Mage::app()->reinitStores();
                } else {
			$this->_getSession()->addError(Mage::helper('capayable')->__('Failed to send the invoice.'));
                }

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                Mage::logException($e);
            }
        }
    }

    /**
     * If order paid with capayable payment method then disable creating order invoice.
     * Invoice will be created automatically only after creating shipment.
     *
     * @param Varien_Event_Observer $observer
     */
    public function disableOrderInvoice(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $_order = $event->getOrder();
        $_payment = $_order->getPayment();

        /**
         * Set order invoice flag to false
         *
         * @see Mage_Sales_Model_Order::canInvoice();
         * @see Mage_Adminhtml_Block_Sales_Order_View::__construct(); Do not add invoice button to adminhtml view.
         */
        if($_payment->getMethod() == Mage::getModel('capayable/payment')->getCode()) {
            $_order->setActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_INVOICE, false);
        }
    }

    /**
     * Retrieve adminhtml session model object
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}