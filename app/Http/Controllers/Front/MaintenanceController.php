<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Mail;

class MaintenanceController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCache()
    {
        
        //apc_clear_cache();
        Artisan::call('config:cache');
        Cache::flush();
        Artisan::call('cache:clear');
        /*Artisan::call('optimize');*/
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        return response('All Cache Cleared', 200)->header('Content-Type', 'text/plain');
    }

    public function Paymentcheck()
	{
		$payGateId = '10011072130';
        $encryptionKey = 'test';
	    include_once(public_path().'/lib/php/global.inc.php');
        include_once(public_path().'/lib/php/paygate.payhost_soap.php');

        //$vaultId = $vaultDetails->vault_id;
        $vaultId = '33a58dd1-687d-4678-a3ab-8c8001be72cf';
        $CVV = "123";
        $reference = generateReference();
        $tempName = explode(' ','Demo Name');

        $request = "
 <ns1:SinglePaymentRequest>
 	<ns1:CardPaymentRequest>
 		<ns1:Account>
 			<ns1:PayGateId>".$payGateId."</ns1:PayGateId>
 			<ns1:Password>".$encryptionKey."</ns1:Password>
 		</ns1:Account>
 		<ns1:Customer>
 			<ns1:FirstName>".$tempName[0]."</ns1:FirstName>
 			<ns1:LastName>".$tempName[1]."</ns1:LastName>
 			<ns1:Telephone>0123456789</ns1:Telephone>
 			<ns1:Mobile>0123456789</ns1:Mobile>
 			<ns1:Email>test@test.com</ns1:Email>
 		</ns1:Customer>
 		<ns1:VaultId>".$vaultId."</ns1:VaultId>
 		<ns1:CVV>".$CVV."</ns1:CVV>
 		<ns1:Redirect>
 			<ns1:NotifyUrl>https://www.jointly.co.za/paymentNotify</ns1:NotifyUrl>
 			<ns1:ReturnUrl>https://www.jointly.co.za/paymentResult</ns1:ReturnUrl>
 		</ns1:Redirect>
 		<ns1:Order>
 			<ns1:MerchantOrderId>".$reference."</ns1:MerchantOrderId>
 			<ns1:Currency>ZAR</ns1:Currency>
 			<ns1:Amount>5</ns1:Amount>
 			<ns1:OrderItems>
 				<ns1:ProductCode>1234</ns1:ProductCode>
 				<ns1:ProductDescription>Title</ns1:ProductDescription>
 				<ns1:ProductCategory>Type</ns1:ProductCategory>
 				<ns1:ProductRisk>XX</ns1:ProductRisk>
 				<ns1:OrderQuantity>1</ns1:OrderQuantity>
 				<ns1:UnitPrice>5</ns1:UnitPrice>
 				<ns1:Currency>ZAR</ns1:Currency>
 			</ns1:OrderItems>
 		</ns1:Order>
 	</ns1:CardPaymentRequest>
 </ns1:SinglePaymentRequest>";

    
// $request = <<<XML
// <ns1:SinglePaymentRequest>
// 	<ns1:CardPaymentRequest>
// 		<ns1:Account>
// 			<ns1:PayGateId>{$payGateId}</ns1:PayGateId>
// 			<ns1:Password>{$encryptionKey}</ns1:Password>
// 		</ns1:Account>
// 		<ns1:Customer>
// 			<!-- Optional: -->
// 			<ns1:Title>Mr</ns1:Title>
// 			<ns1:FirstName>PayGate</ns1:FirstName>
// 			<!-- Optional: -->
// 			<!-- <ns1:MiddleName>?</ns1:MiddleName> -->
// 			<ns1:LastName>Test</ns1:LastName>
// 			<!-- Zero or more repetitions: -->
// 			<ns1:Telephone>0861234567</ns1:Telephone>
// 			<!-- Zero or more repetitions: -->
// 			<ns1:Mobile>0842573344</ns1:Mobile>
// 			<!-- Zero or more repetitions: -->
// 			<ns1:Fax>086009991111</ns1:Fax>
// 			<!-- 1 or more repetitions: -->
// 			<ns1:Email>itsupport@paygate.co.za</ns1:Email>
// 		</ns1:Customer>
// 		<ns1:VaultId>{$vaultId}</ns1:VaultId>
// 		<ns1:CVV>{$CVV}</ns1:CVV>
// 		<ns1:Redirect>
// 			<ns1:NotifyUrl>https://www.jointly.co.za/paymentNotify</ns1:NotifyUrl>
// 			<ns1:ReturnUrl>https://www.jointly.co.za/paymentResult</ns1:ReturnUrl>
// 			<!--  <ns1:Target>_self | _parent</ns1:Target>  -->
// 		</ns1:Redirect>
// 		<ns1:Order>
// 			<ns1:MerchantOrderId>{$reference}</ns1:MerchantOrderId>
// 			<ns1:Currency>ZAR</ns1:Currency>
// 			<ns1:Amount>10</ns1:Amount>
// 			<ns1:OrderItems>
// 				<ns1:ProductCode>ABC123</ns1:ProductCode>
// 				<ns1:ProductDescription>Misc Product</ns1:ProductDescription>
// 				<ns1:ProductCategory>misc</ns1:ProductCategory>
// 				<ns1:ProductRisk>XX</ns1:ProductRisk>
// 				<ns1:OrderQuantity>1</ns1:OrderQuantity>
// 				<ns1:UnitPrice>12311</ns1:UnitPrice>
// 				<ns1:Currency>ZAR</ns1:Currency>
// 			</ns1:OrderItems>
// 		</ns1:Order>
// 	</ns1:CardPaymentRequest>
// </ns1:SinglePaymentRequest>
// XML;

              
                /**
                 *  disabling WSDL cache
                 */
                ini_set("soap.wsdl_cache_enabled", "0");

                /*
                * Using PHP SoapClient to handle the request
                */
                $soapClient = new \SoapClient('https://secure.paygate.co.za/PayHost/process.trans?wsdl', array('trace' => 1)); //point to WSDL and set trace value to debug

                try {
                    /*
                    * Send SOAP request
                    */
                    $result = $soapClient->__soapCall('SinglePayment', array(
                        new \SoapVar($request, XSD_ANYXML)
                    ));

                    $response = $soapClient->__getLastResponse();

                    $clean_xml = str_replace(['ns2:',':ns2','ns1:',':ns1'], '', $response);

                    $xml = simplexml_load_string($clean_xml);
                    $xml->registerXPathNamespace('ns2', 'http://www.paygate.co.za/PayHOST');
                    $elements = $xml->xpath('//SOAP-ENV:Envelope/SOAP-ENV:Body/ns2:SinglePaymentResponse/ns2:CardPaymentResponse/ns2:Status');
                    
                    $result = (array)$elements[0];

                    echo '<pre>';
                    print_r($result);
                    echo '</pre>';
                    
                } catch (SoapFault $sf) {
                    /*
                    * handle errors
                    */
                    $err = $sf->getMessage();
                  }
        
    }
    
    function paymentNotify(Request $request)
    {
        $email = 'shrik@techbeeconsulting.com';
        $name = 'name';
        $arrMailData = ['first_name' => 'first', 'email' => 'shrik@techbeeconsulting.com', 'last_name' => 'last'];
        //return view('emails/confirm_sale', $arrMailData);
        Mail::send('emails/contact_us', $arrMailData, function($message) use ($email,$name) {
            $message->to($email, $name)->subject
                ('Jointly - Notify');
            $message->from('noreply@jointly.co.za','Jointly');
        });
    }

    function paymentResult(Request $request)
    {
        $email = 'shrik@techbeeconsulting.com';
        $name = 'name';
        $arrMailData = ['first_name' => 'first', 'email' => 'shrik@techbeeconsulting.com', 'last_name' => 'last'];
        //return view('emails/confirm_sale', $arrMailData);
        Mail::send('emails/contact_us', $arrMailData, function($message) use ($email,$name) {
            $message->to($email, $name)->subject
                ('Jointly - Result');
            $message->from('noreply@jointly.co.za','Jointly');
        });
    }
}
