<?php

namespace App\Repositories\Paypal;

use App\Helpers\Constants;
use App\Models\Log\LogFollow;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\BaseRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Address;
use PayPal\Api\BillingInfo;
use PayPal\Api\Cost;
use PayPal\Api\Currency;
use PayPal\Api\Invoice;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\InvoiceItem;
use PayPal\Api\MerchantInfo;
use PayPal\Api\PaymentTerm;
use PayPal\Api\Phone;
use PayPal\Api\ShippingInfo;
use PHPUnit\TextUI\ResultPrinter;
use Symfony\Component\Console\Helper\Helper;

class PaypalRepo
{
    public function __construct()
    {
    }

    public function getToken()
    {
        $client_id = Constants::PAYPAL_CLIENT_ID;
        $client_secret = Constants::PAYPAL_CLIENT_SECRET;
        $apiContext = new \PayPal\Auth\OAuthTokenCredential($client_id, $client_secret);
        return $apiContext;
    }

    public function createdInvoice($params)
    {
        $invoice = new Invoice();

        $invoice
            ->setMerchantInfo(new MerchantInfo())
            ->setBillingInfo(array(new BillingInfo()));
            // ->setPaymentTerm(new PaymentTerm())
            // ->setShippingInfo(new ShippingInfo());
        if($params->note_sale){
            $invoice->setNote($params->note_sale);
        }
        $invoice->getMerchantInfo()
            ->setEmail(Constants::EMAIL)
            ->setFirstName(Constants::FIRST_NAME)
            ->setLastName(Constants::LAST_NAME)
            ->setbusinessName(Constants::COMPANY_NAME)
            // ->setPhone(new Phone())
            ->setAddress(new Address());

        // $invoice->getMerchantInfo()->getPhone()
        //     ->setCountryCode("84")
        //     ->setNationalNumber(Constants::PHONE);

        $invoice->getMerchantInfo()->getAddress()
            ->setLine1(Constants::ADDESS);

        $billing = $invoice->getBillingInfo();
        $billing[0]
            ->setEmail($params->customer->email_paypal)
            ->setFirstName($params->customer->fullname);

        // $billing[0]->setBusinessName("")
        //     ->setAdditionalInfo("This is the billing Info");
            // ->setAddress(new InvoiceAddress());

        // $billing[0]->getAddress()
        //     ->setLine1("1234 Main St.")
        //     ->setCity("Portland")
        //     ->setState("OR")
        //     ->setPostalCode("97217")
        //     ->setCountryCode("US");
        $items = [];
        if(isset($params['details']) && count($params['details']) > 0){
            foreach($params['details'] as $key => $item){
                $item_i = new InvoiceItem();
                $item_i
                ->setName($item->order_name)
                ->setDescription($item->description)
                ->setQuantity($item->quantity)
                ->setUnitPrice(new Currency());
    
                $item_i->getUnitPrice()
                ->setCurrency("USD")
                ->setValue($item->price);
                $items[] = $item_i;
            }
        }
        
        // $tax = new \PayPal\Api\Tax();
        // $tax->setPercent(1)->setName("Local Tax on Sutures");
        // $items[0]->setTax($tax);

        // $item1discount = new Cost();
        // $item1discount->setPercent("3");
        // $items[1]
        //     ->setName("Injection")
        //     ->setQuantity(5)
        //     ->setDiscount($item1discount)
        //     ->setUnitPrice(new Currency());

        // $items[1]->getUnitPrice()
        //     ->setCurrency("USD")
        //     ->setValue(5);

        // $tax2 = new \PayPal\Api\Tax();
        // $tax2->setPercent(3)->setName("Local Tax on Injection");
        // $items[1]->setTax($tax2);

        $invoice->setItems($items);

        //Giảm giá trên tổng đơn hàng
        if(isset($params['order']) && $params['order']->discount > 0){
            $cost = new Cost();
            $cost->setPercent($params['order']->discount);
            $invoice->setDiscount($cost);
        }
        if(isset($params['order']) && $params['order']->discount_money > 0){
            $invoice->setDiscount($params['order']->discount_money);
        }

        // $invoice->getPaymentTerm()
        //     ->setTermType("NET_45");
        
        //Giao hàng
        // $invoice->getShippingInfo()
        //     ->setFirstName("Sally")
        //     ->setLastName("Patient")
        //     ->setBusinessName("Not applicable")
        //     ->setPhone(new Phone())
        //     ->setAddress(new InvoiceAddress());

        // $invoice->getShippingInfo()->getPhone()
        //     ->setCountryCode("001")
        //     ->setNationalNumber("5039871234");

        // $invoice->getShippingInfo()->getAddress()
        //     ->setLine1("1234 Main St.")
        //     ->setCity("Portland")
        //     ->setState("OR")
        //     ->setPostalCode("97217")
        //     ->setCountryCode("US");

        // $invoice->setLogoUrl('https://www.paypalobjects.com/webstatic/i/logo/rebrand/ppcom.svg');

        $request = clone $invoice;

        $client_id = Constants::PAYPAL_CLIENT_ID;
        $client_secret = Constants::PAYPAL_CLIENT_SECRET;
        $auth = new \PayPal\Auth\OAuthTokenCredential(
            $client_id,
            $client_secret
        );
        $apiContext = new \PayPal\Rest\ApiContext($auth);
        $token = $auth->getAccessToken($apiContext->getConfig());
        // echo $token;
        // die();

        $res = [
            'created_invoice' => true,
            'send_invoice' => true,
            'id_invoice' => '',
            'detail' => null
        ];

        try {

            $invoice->create($apiContext);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
            exit(1);
        }

        $id = $invoice->getId();
        $res['id_invoice'] = $id;
        // return $id;
        // $id = 'INV2-SNXH-L3QA-ZCHZ-HZ34';
        //Lấy lại thông thông tin hóa đơn
        // if(!empty($id)){
        //     try {
        //         $detail_invoice = Invoice::get($id, $apiContext);
        //     }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
        //     echo $ex->getData();
        //     exit(1);
        // }
        // }
        
        //Send Invoice
        try {
            $result = $invoice->send($apiContext);
            $detail_invoice = Invoice::get($id, $apiContext);
            $res['detail'] = $detail_invoice;
            $res['link_paypal'] = ($detail_invoice->metadata->payer_view_url) ? $detail_invoice->metadata->payer_view_url : $detail_invoice->metadata->recipient_view_url;
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $url = 'https://api-m.paypal.com/v2/invoicing/invoices/'.$id.'/send';
            $post = ['send_to_invoicer' => true, 'send_to_recipient'=> true];
            $result = sendRequest($url,
            $post,
            'POST', true, false, $token, 15, true);
            if(isset($result->status) && $result->status == 200){
                $detail_invoice = Invoice::get($id, $apiContext);
                $res['link_paypal'] = ($detail_invoice->metadata->payer_view_url) ? $detail_invoice->metadata->payer_view_url : $detail_invoice->metadata->recipient_view_url;
                $res['detail'] = $detail_invoice;
            } else{
                $res['send_invoice'] = false;
            }
        }

        return $res;
    }

    public function updateInvoice($params)
    {
        $client_id = Constants::PAYPAL_CLIENT_ID;
        $client_secret = Constants::PAYPAL_CLIENT_SECRET;
        $auth = new \PayPal\Auth\OAuthTokenCredential(
            $client_id,
            $client_secret
        );
        $apiContext = new \PayPal\Rest\ApiContext($auth);
        $token = $auth->getAccessToken($apiContext->getConfig());

        $invoice = Invoice::get($params->paypal_id, $apiContext);

        $invoice
        ->setMerchantInfo(new MerchantInfo())
        ->setBillingInfo(array(new BillingInfo()));
        if($params->note_sale){
            $invoice->setNote($params->note_sale);
        }
        $invoice->getMerchantInfo()
        ->setEmail(Constants::EMAIL)
        ->setFirstName(Constants::FIRST_NAME)
        ->setLastName(Constants::LAST_NAME)
        ->setbusinessName(Constants::COMPANY_NAME);
        $items = [];
        if(isset($params['details']) && count($params['details']) > 0){
            foreach($params['details'] as $key => $item){
                $item_i = new InvoiceItem();
                $item_i
                ->setName($item->order_name)
                ->setQuantity($item->quantity)
                ->setUnitPrice(new Currency());
    
                if($item->description){
                    $item_i->setDescription($item->description);
                }
                $item_i->getUnitPrice()
                ->setCurrency("USD")
                ->setValue($item->price);
                $items[] = $item_i;
            }
        }
        $invoice->setItems($items);

        //Giảm giá trên tổng đơn hàng
        if(isset($params['order']) && $params['order']->discount > 0){
            $cost = new Cost();
            $cost->setPercent($params['order']->discount);
            $invoice->setDiscount($cost);
        }
        if(isset($params['order']) && $params['order']->discount_money > 0){
            $invoice->setDiscount($params['order']->discount_money);
        }

        
        $request = clone $invoice;

        // echo $token;
        // die();

        $res = [
            'updated_invoice' => true,
            'send_invoice' => true,
            'id_invoice' => '',
            'detail' => null
        ];

        try {

            // $invoice->create($apiContext);
            $invoice->update($apiContext);
            // print_r('ssszzs:');
            // print_r($invoice);
            // die();
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $res['detail'] = $ex->getData();
            $res['updated_invoice'] = false;
            
        }

        $id = $invoice->getId();
        $res['id_invoice'] = $id;

        return $res;
    }
    public function checkStatus($paypal_id){
        
        $client_id = Constants::PAYPAL_CLIENT_ID;
        $client_secret = Constants::PAYPAL_CLIENT_SECRET;
        $auth = new \PayPal\Auth\OAuthTokenCredential(
            $client_id,
            $client_secret
        );
        $apiContext = new \PayPal\Rest\ApiContext($auth);
        $token = $auth->getAccessToken($apiContext->getConfig());
        $invoice = Invoice::get($paypal_id, $apiContext);
        // print_r($invoice);
        // die();


        if($invoice){
            return $invoice->status;
        } else{
            return false;
        }
    }
}
