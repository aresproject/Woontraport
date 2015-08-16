<?php
/*----------OBJECTIVES-----------
1. Establish Woo Connection
2. Set Filters
   Date/time of order created/updated
3. Get Woo Data
4. Pass woo data to ontra
5. establish Ontra connection and pass the data 
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

function ontra_sale($cid, $prodid, $price, $trandate, $order_num){
	$data = <<<STRING
	<purchases contact_id=$cid product_id=$prodid />
	<field name="Price">$price</field>
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
}

try {

	$client = new WC_API_Client( 'http://sandbox2.strachanonline.com', 'ck_c1c9fea015d2da2291331f32d03950ad', 'cs_b2a193a9a1b2c2dae3d248d770efd911', $options );

	//Filter on Customer ID
	/*print_r($client->orders->get(null,array( 'status' => 'completed', 
	    'filter[created_at_min]' => '2015-06-14',
	    'filter[created_at_max]' => '2015-06-16') ) ); */

	//Filter on Date Range
	$datum = $client->orders->get(null,array( 'status' => 'completed', 
	    'filter[created_at_min]' => '2015-06-14',
	    'filter[created_at_max]' => '2015-06-19', 'fields' => 'id,status,order_number,updated_at,total,line_items,billing_address,customer') );
	
	$array = get_object_vars($datum);
	echo "Datum is " . count($datum->orders);
	echo "Array is " . count($array['orders']);
	echo "<br />";
	//print_r($datum);
	/*if(is_array($array)){
		echo "array is an array <br />";
	} 
	if(is_array($datum)) echo "datum ay Array pala"; else echo "datum ay object talaga to <br />";
	;
	$count = count($datum);*/
	/*for($i=0; $i < count($datum->orders); $i++){
		echo "ID:" . $datum->orders[$i]->id . "<br />";
		echo "Status:" . $datum->orders[$i]->status . "<br />";
		echo "Order Number:" . $datum->orders[$i]->order_number . "<br />";
	}*/
	foreach($datum->orders as $orders => $val){
		//print_r($orders);
		print_r("ID: " . $val->id . "<br />");
		print_r("Status: " . $val->status . "<br />");
		print_r("Order Number: " . $val->order_number . "<br />");
		print_r("Updated At: " . $val->updated_at . "<br />");
		print_r("Total: " . $val->total . "<br />");
		print_r("Customer email: " . $val->customer->email . "<br />" );
		print_r("Customer Name: " . $val->customer->last_name . ", " . $val->customer->first_name . "<br />" );
		foreach($val->line_items as $item){
			print_r("item #: " . $item->id . "<br />" );
			print_r("Product Name: " . $item->name . "<br />" );
			$ontitem = $item->name;
			switch($ontitem) {
				case "TermiteTrap 1:1 Kit":
					echo "Ontra ID is " . 2 . "<br />";
					$ontraid = 2;
					break;
				case "TermiteTrap Timber Refills":
					echo "Ontra ID is " . 3 . "<br />";
					$ontraid = 3;
					break;
				case "TermiteTraps":
					echo "Ontra ID is " . 4 . "<br />";
					$ontraid = 4;
					break;
				case "Termite Trap Colony Killing System":
					echo "Ontra ID is " . 5 . "<br />";
					$ontraid = 5;
					break;
				case "Colony Killer Termite Bait (Pouches)":
					echo "Ontra ID is " . 6 . "<br />";
					$ontraid = 6;
					break;
				case "TermiteTrap 3:1 Kit":
					echo "Ontra ID is " . 7 . "<br />";
					$ontraid = 7;
					break;
				case "Australian TermiteTraps":
					echo "Ontra ID is " . 8 . "<br />";
					$ontraid = 8;
					break;
				case "Colony Killer Termite Bait (Tubs)":
					echo "Ontra ID is " . 9 . "<br />";
					$ontraid = 9;
					break;
				case "TermiteTrap 2:1 Kit":
					echo "Ontra ID is " . 10 . "<br />";
					$ontraid = 10;
					break;
			}
			print_r("Product Id: " . $item->product_id . "<br />" );
			print_r("Qty: " . $item->quantity . "<br />" );
		}
		echo "----------------------------------------------- <br />";
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




