<?php
class PayPallib {
      
  var $PayPalMode       = 'live'; // sandbox or live
  var $PayPalApiUsername    = ''; //PayPal API Username
  var $PayPalApiPassword    = ''; //Paypal API password
  var $PayPalApiSignature   = ''; //Paypal API Signature
  var $PayPalCurrencyCode   = 'USD'; //Paypal Currency Code
  var $PayPalReturnURL    = ''; //Point to process.php page
  var $PayPalCancelURL    = ''; //Cancel URL if user clicks cancel    
  var $logoURL        = '';//el logo lo pongo fijo aca
      
    function setup($args) {
      $this->PayPalReturnURL = $args['returnURL'];
      $this->PayPalCancelURL = $args['cancelURL'];
      $this->PayPalApiUsername = $args['API_Username'];
      $this->PayPalApiPassword = $args['API_Password'];
      $this->PayPalApiSignature = $args['API_Signature'];
      //$this->logoURL = $args['LogoURL'];
      $this->PayPalCurrencyCode = $args['currency'];
    }
    
    function sandbox($enable) {
      if ($enable) {
        $this->PayPalMode = 'sandbox';
      }
      else {
        $this->PayPalMode = 'live';
      }
    }
      
  function checkout($item, $total, $taxes=0) {
    $paypalmode = ($this->PayPalMode=='sandbox') ? '.sandbox' : '';

    //echo $paypalmode;

    $ItemName     = $item;
    //$ItemPrice    = $total;
    //28-02-19 el valor TOTAL es por el pago del usuario, pero NO LE CARGO LOS GASTOS, los absorve BV, por ende al importe pagado por el pax, le descuento el valor de TAXES,
    $ItemPrice    = $total-$taxes;

    //$ItemNumber   = $code;
    //$ItemDesc     = $description;
    $ItemQty    = "1";
    $ItemTotalPrice = number_format(($ItemPrice*$ItemQty),2,'.',''); //(Item Price x Quantity = Total) Get total amount of product; 
    
    //Other important variables like tax, shipping cost
    $TotalTaxAmount   = number_format($taxes,2,'.','');  //Sum of tax for all items in this order.
  
    $HandalingCost    = 0;  //Handling cost for this order.
    $InsuranceCost    = 0;  //shipping insurance cost for this order.
    $ShippinDiscount  = 0; //Shipping discount for this order. Specify this as negative number.
    $ShippinCost    = 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    
    //Grand total including all tax, insurance, shipping cost and discount
    $GrandTotal = number_format(($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount),2,'.','');
    
    //Parameters for SetExpressCheckout, which will be sent to PayPal
    $padata =   '&METHOD=SetExpressCheckout'.
          '&RETURNURL='.urlencode($this->PayPalReturnURL ).
          '&CANCELURL='.urlencode($this->PayPalCancelURL).
          '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
          
          '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
          //'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
          //'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
          '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
          '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
          
          /* 
          //Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
          '&ADDROVERRIDE=1'.
          '&PAYMENTREQUEST_0_SHIPTONAME=J Smith'.
          '&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St'.
          '&PAYMENTREQUEST_0_SHIPTOCITY=San Jose'.
          '&PAYMENTREQUEST_0_SHIPTOSTATE=CA'.
          '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
          '&PAYMENTREQUEST_0_SHIPTOZIP=95131'.
          '&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444'.
          */
          
          '&NOSHIPPING=1'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
          
          '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
          '&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
          '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
          '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
          '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
          '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
          '&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
          '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->PayPalCurrencyCode).
          '&LOCALECODE=EN'. //PayPal pages to match the language on your website.
          '&LOGOIMG='.$this->logoURL. //site logo
          '&CARTBORDERCOLOR=FFFFFF'. //border color of cart
          '&ALLOWNOTE=0';

      #pre($padata);

      ############# set session variable we need later for "DoExpressCheckoutPayment" #######
      $_SESSION['ItemName']       =  $ItemName; //Item Name
      $_SESSION['ItemPrice']      =  $ItemPrice; //Item Price
      //$_SESSION['ItemNumber']     =  $ItemNumber; //Item Number
      //$_SESSION['ItemDesc']       =  $ItemDesc; //Item Number
      $_SESSION['ItemQty']      =  $ItemQty; // Item Quantity
      $_SESSION['ItemTotalPrice']   =  $ItemTotalPrice; //(Item Price x Quantity = Total) Get total amount of product; 
      $_SESSION['TotalTaxAmount']   =  $TotalTaxAmount;  //Sum of tax for all items in this order. 
      $_SESSION['HandalingCost']    =  $HandalingCost;  //Handling cost for this order.
      $_SESSION['InsuranceCost']    =  $InsuranceCost;  //shipping insurance cost for this order.
      $_SESSION['ShippinDiscount']  =  $ShippinDiscount; //Shipping discount for this order. Specify this as negative number.
      $_SESSION['ShippinCost']    =   $ShippinCost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
      $_SESSION['GrandTotal']     =  $GrandTotal;
  
      //We need to execute the "SetExpressCheckOut" method to obtain paypal token
      $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata, $this->PayPalApiUsername, $this->PayPalApiPassword, $this->PayPalApiSignature, $this->PayPalMode);
  
      #pre($httpParsedResponseAr);

      //Respond according to message we receive from Paypal
      if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
      {
        //Redirect user to PayPal store with Token received.
        $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
        //header('Location: '.$paypalurl);
         return array('url' => $paypalurl, 'status' => 'OK');
      }
      else {
        //Show error message
        $code = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);

        return array('code' => $code, 'status' => 'ERROR');
      }
  }
      
    function process() {
    //Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
    if(isset($_GET["token"]) && isset($_GET["PayerID"]))
    {
      //we will be using these two variables to execute the "DoExpressCheckoutPayment"
      //Note: we haven't received any payment yet.
      
      $token = $_GET["token"];
      $payer_id = $_GET["PayerID"];
      
      //get session variables
      $ItemName       = $_SESSION['ItemName']; //Item Name
      $ItemPrice      = $_SESSION['ItemPrice'] ; //Item Price
      //$ItemNumber     = $_SESSION['ItemNumber']; //Item Number
      //$ItemDesc       = $_SESSION['ItemDesc']; //Item Number
      $ItemQty      = $_SESSION['ItemQty']; // Item Quantity
      $ItemTotalPrice   = $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product; 
      $TotalTaxAmount   = $_SESSION['TotalTaxAmount'] ;  //Sum of tax for all items in this order. 
      $HandalingCost    = $_SESSION['HandalingCost'];  //Handling cost for this order.
      $InsuranceCost    = $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
      $ShippinDiscount  = $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
      $ShippinCost    = $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
      $GrandTotal     = $_SESSION['GrandTotal'];
    
      $padata =   '&TOKEN='.urlencode($token).
            '&PAYERID='.urlencode($payer_id).
            '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
            
            //set item info here, otherwise we won't see product details later  
            '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
          //  '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
          //  '&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
            '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
            '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
    
            /* 
            //Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
            '&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
            '&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
            '&L_PAYMENTREQUEST_0_DESC1=Description text'.
            '&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
            '&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
            */
    
            '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
            '&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
            '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
            '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
            '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
            '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
            '&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
            '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->PayPalCurrencyCode);
      
      //We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
      $httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $padata, $this->PayPalApiUsername, $this->PayPalApiPassword, $this->PayPalApiSignature, $this->PayPalMode);
      

      //devuelvo estos valores tambien
      $data['total_price'] = $ItemTotalPrice+$TotalTaxAmount;
      $data['item_price'] = $ItemPrice;
      $data['tax_amount'] = $TotalTaxAmount;
      $data['transaction_id'] = '';

      //Check if everything went ok..
      if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
      {
        $transaction_id = urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
        $data['transaction_id'] = $transaction_id;
        
        /*
        //Sometimes Payment are kept pending even when transaction is complete. 
        //hence we need to notify user about it and ask him manually approve the transiction
        */
        if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
        {
           $data['status'] = 'completed';
           $data['description'] = 'Payment completed';
        }
        elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
        {
          $data['status'] = 'pending';
          $data['description'] = 'Payment processed but still pending';
        }
    
        // we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        $padata =   '&TOKEN='.urlencode($token);
        $httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, $this->PayPalApiUsername, $this->PayPalApiPassword, $this->PayPalApiSignature, $this->PayPalMode);
    
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
        {
          $buyerName = $httpParsedResponseAr["FIRSTNAME"].' '.$httpParsedResponseAr["LASTNAME"];
          $buyerEmail = $httpParsedResponseAr["EMAIL"];

          /*              
          echo '<pre>';
          print_r($httpParsedResponseAr);
          echo '</pre>';
          */
        } else  {
          $data['status'] = 'failed';
          $data['description'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        }
      
      }
      else{
        $data['status'] = 'failed';
        $data['description'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
      }
      
      return $data;
    }  
    else {
      return FALSE;
    }
  }  
      
  function PPHttpPost($methodName_, $nvpStr_, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode) {
      // Set up your API credentials, PayPal end point, and API version.
      $API_UserName = urlencode($PayPalApiUsername);
      $API_Password = urlencode($PayPalApiPassword);
      $API_Signature = urlencode($PayPalApiSignature);
      
      $paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';
  
      $API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
      $version = urlencode('109.0');
    
      // Set the curl parameters.
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
      // Turn off the server and peer verification (TrustManager Concept).
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
    
      // Set the API operation, version, and API signature in the request.
      $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
    
      // Set the request as a POST FIELD for curl.
      curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
    
      // Get response from the server.
      $httpResponse = curl_exec($ch);
    
      if(!$httpResponse) {
        exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
      }
    
      // Extract the response details.
      $httpResponseAr = explode("&", $httpResponse);
    
      $httpParsedResponseAr = array();
      foreach ($httpResponseAr as $i => $value) {
        $tmpAr = explode("=", $value);
        if(sizeof($tmpAr) > 1) {
          $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
        }
      }
    
      if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
        exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
      }
    
    return $httpParsedResponseAr;
  }   
}
?>