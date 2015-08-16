<?php

//Multiple search queries can be sent by separating with commas
//Multiple equations work as an AND
$data = <<<STRING
<search>
	<equation>
		<field>E-mail</field>
		<op>c</op>
		<value>aresproj3ct@gmail.com</value>
	</equation>
</search>
STRING;

$data = urlencode(urlencode($data));

// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
$appid = "2_26778_uRluSOFwD";
$key = "Ffb5u9ypIWGHeg6";

$reqType = "search";
$postargs = "appid=".$appid."&key=".$key."&reqType=".$reqType."&data=".$data;
$request = "http://api.ontraport.com/cdata.php";

$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt ($session, CURLOPT_HEADER, false);
curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($session);
$header=substr($response,0,curl_getinfo($session,CURLINFO_HEADER_SIZE));
$body=substr($response,curl_getinfo($session,CURLINFO_HEADER_SIZE));

echo $header;
echo "<br />";
echo $body;
echo "<br />";
echo $response; //display the result;

curl_close($session);
