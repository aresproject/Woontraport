<?php

//Override any of the existing product information for the purchase by passing optional fields
$data = <<<STRING
<purchases contact_id="8351" product_id='10' />
<field name="Price">10.00</field>
STRING;

$data = urlencode(urlencode($data));

// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
$appid = "2_26778_uRluSOFwD";
$key = "Ffb5u9ypIWGHeg6";

$reqType= "sale";
$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
$request = "http://api.ontraport.com/pdata.php";

$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($session);
echo $response;
curl_close($session);