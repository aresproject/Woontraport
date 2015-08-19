<?php
/*----------OBJECTIVES-----------
1. Establish Woo Connection
2. Set Filters
   2.1 Date/time of order created/updated
3. Get Woo Data
4. Pass woo customer email data to ontra search module
   4.1 return ontra contact id from search
5. Run Ontra Sale Module by giving contact id from search
and order details of woo.

*** ideally find out how to create a listener for new orders in woo
so that it will be automatically added to ontraport
-------------------------------*/

require_once( 'lib/woocommerce-api.php' );

$options = array(
	'debug'           => true,
	'return_as_array' => false,
	'validate_url'    => false,
	'timeout'         => 30,
	'ssl_verify'      => false,
);

$order_id = "";
$appid = "2_26778_uRluSOFwD";
$key = "Ffb5u9ypIWGHeg6";

function ontra_sale($cid, $prodid, $price){
	echo "<br />";
	echo $cid . " " . $prodid . " " . $price;
	echo "<br />";
	$data = "<purchases contact_id=\"" . $cid . "\" product_id=\"" . $prodid . "\"/><field name=\"Price\">" . $price . "</field>";
	//echo $data;
	
	$data = urlencode(urlencode($data));

	// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
	$appid = "2_26778_uRluSOFwD";
	$key = "Ffb5u9ypIWGHeg6";

	$reqType= "sale";
	$postargs = "appid=".$GLOBALS['appid']."&key=".$GLOBALS['key']."&return_id=1&reqType=".$reqType."&data=".$data;
	$request = "http://api.ontraport.com/pdata.php";

	$session = curl_init($request);
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($session);
	echo $response;
	curl_close($session);
	
}

function ontra_search($email){
	$data = "<search><equation>
		<field>E-mail</field>
		<op>c</op>
		<value>$email</value>
	</equation>
	</search>";

	$reqType = "search";
	$postargs = "appid=" . $GLOBALS['appid']. "&key=" .$GLOBALS['key']. "&reqType=".$reqType."&data=".$data;
	$request = "http://api.ontraport.com/cdata.php";

	$session = curl_init($request);
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
	curl_setopt ($session, CURLOPT_HEADER, false);
	curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($session);
	$header=substr($response,0,curl_getinfo($session,CURLINFO_HEADER_SIZE));
	$body=substr($response,curl_getinfo($session,CURLINFO_HEADER_SIZE));

	$xml = simplexml_load_string($response);
	curl_close($session);
	$cid = xml_attribute($xml->contact, 'id');
	return $cid;
}

function xml_attribute($object, $attribute){
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}


try {

	$client = new WC_API_Client( 'http://sandbox2.strachanonline.com', 'ck_c1c9fea015d2da2291331f32d03950ad', 'cs_b2a193a9a1b2c2dae3d248d770efd911', $options );

	//Filter on Customer ID
	/*print_r($client->orders->get(null,array( 'status' => 'completed', 
	    'filter[created_at_min]' => '2015-06-14',
	    'filter[created_at_max]' => '2015-06-16') ) ); */

	//Filter on Date Range
	$datum = $client->orders->get(null,array( 'status' => 'completed, processing', 
	    'filter[created_at_min]' => '2015-08-19',
	    'filter[created_at_max]' => '2015-08-20', 'fields' => 'id,status,order_number,updated_at,total,line_items,billing_address,customer') );
	
	$array = get_object_vars($datum);
	//echo "Datum is " . count($datum->orders);
	//echo "Array is " . count($array['orders']);
	//echo "<br />";
	
	foreach($datum->orders as $orders => $val){
		//print_r($orders);
		print_r("ID: " . $val->id . "<br />");
		print_r("Status: " . $val->status . "<br />");
		print_r("Order Number: " . $val->order_number . "<br />");
		print_r("Updated At: " . $val->updated_at . "<br />");
		print_r("Total: " . $val->total . "<br />");
		print_r("Customer email: " . $val->customer->email . "<br />" );
		$email = $val->customer->email;
		print_r("Customer Name: " . $val->customer->last_name . ", " . $val->customer->first_name . "<br />" );
		foreach($val->line_items as $item){
			print_r("item #: " . $item->id . "<br />" );
			print_r("Product Name: " . $item->name . "<br />" );
			print_r("Product Price: " . $item->price . "<br />" );
			$price = $item->price;
			$ontitem = $item->name;
			switch($ontitem) {
				case "TermiteTrap 1:1 Kit":
					echo "Ontra Prod ID is " . 2 . "<br />";
					$ontraid = 2;
					break;
				case "TermiteTrap Timber Refills":
					echo "Ontra Prod ID is " . 3 . "<br />";
					$ontraid = 3;
					break;
				case "TermiteTraps":
					echo "Ontra Prod ID is " . 4 . "<br />";
					$ontraid = 4;
					break;
				case "Termite Trap Colony Killing System":
					echo "Ontra Prod ID is " . 5 . "<br />";
					$ontraid = 5;
					break;
				case "Colony Killer Termite Bait (Pouches)":
					echo "Ontra Prod ID is " . 6 . "<br />";
					$ontraid = 6;
					break;
				case "TermiteTrap 3:1 Kit":
					echo "Ontra Prod ID is " . 7 . "<br />";
					$ontraid = 7;
					break;
				case "Australian TermiteTraps":
					echo "Ontra Prod ID is " . 8 . "<br />";
					$ontraid = 8;
					break;
				case "Colony Killer Termite Bait (Tubs)":
					echo "Ontra Prod ID is " . 9 . "<br />";
					$ontraid = 9;
					break;
				case "TermiteTrap 2:1 Kit":
					echo "Ontra Prod ID is " . 10 . "<br />";
					$ontraid = 10;
					break;
			}
			print_r("Product Id: " . $item->product_id . "<br />" );
			print_r("Qty: " . $item->quantity . "<br />" );
			$cid = ontra_search($email);
			echo "<br /> Ontra ID of Email is: ";
			echo $cid;
			ontra_sale($cid, $ontraid, $price);

		}
		echo "<br />----------------------------------------------- <br />";
	}
}



catch ( WC_API_Client_Exception $e ) {

	echo $e->getMessage() . PHP_EOL;
	echo $e->getCode() . PHP_EOL;

	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		print_r( $e->get_request() );
		print_r( $e->get_response() );
	}
}




