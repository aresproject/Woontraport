<?php

//Multiple contacts can be fetched by sending separate contact IDs
$data = <<<STRING
<contact_id>16518</contact_id>
<contact_id>8351</contact_id>
STRING;
/*$data = <<<STRING
	<contact_email>aresproj3ct@gmail.com</contact_email>
STRING;*/

$data = urlencode(urlencode($data));
// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
$appid = "2_26778_uRluSOFwD";
$key = "Ffb5u9ypIWGHeg6";

$reqType= "fetch";
$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
$request = "http://api.ontraport.com/cdata.php";
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($session);
echo "<pre>"; 
//print_r($response, true);
echo $response;
echo "</pre>";
echo gettype($response);
curl_close($session);

