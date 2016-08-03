 <?php
  /*$curl_handle=curl_init();
  curl_setopt($curl_handle,CURLOPT_URL,'http://www.google.com');
  curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
  curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
  $buffer = curl_exec($curl_handle);
  curl_close($curl_handle);
  if (empty($buffer)){
      print "Nothing returned from url.<p>";
  }
  else{
      print $buffer;
  }
  die;*/
?>

<?php /*$data = json_decode('{"fulfillment": {"tracking_url": "","tracking_company": "abc","line_items": [{"id": 466157049},{"id": 518995019},{"id": 703073504}]}}',true);
		$data['file'] = $counts_url;
print_r($data);
die();*/
?>

<?php
error_reporting(E_ALL);
//ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
$products_array = array(
    "fulfillment"=>array(
/*        'tracking_url'=>null,
        "tracking_company"=> "Burton Custom Freestlye 151",*/
	     // "line_items" => "[{'id': 7636006982,'quantity': 1 }]"
        'line_items' =>
                    array(
                       array(
                           'id'=>7636006982,
                           'quantity' => 1
                        ) 
                    )
    )
);
/*
$data_string = json_encode($products_array);                                                                                   

$ch = curl_init('https://6143cddd141541615eb006119cd232be:4f79e7163888d6bf77af717e923f37e9@24sevens.myshopify.com/admin/orders/1031/fulfillments.json');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
print_r($result);*/


echo json_encode($products_array);
die();
$api_url = 'https://6143cddd141541615eb006119cd232be:4f79e7163888d6bf77af717e923f37e9@24sevens.myshopify.com';
echo $url = $api_url . '/admin/orders/3883964166/fulfillments.json';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_VERBOSE, 0);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($products_array));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec ($curl);
curl_close ($curl);
echo "<pre>";
print_r($response); 


