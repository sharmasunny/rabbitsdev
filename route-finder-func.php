<?php
include('db.php');
include('constants.php');
class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
    function pointLocation() {
    }
 
    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
 
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
 
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }
 
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
 
    }
 
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
 
}
$data = $_POST['fdata'];


foreach($_POST['fdata'] as $key=>$value)
{
	if($value['name'] == "productids[]") {
		$post[$value['name']][] .= $value['value'];
	} else {
		$post[$value['name']] = $value['value'];
	}
	
}

$zone_coors = array();
$zonecoquery = "select * from zones";
$zonecoorinates = $conn->query($zonecoquery);
$count = 0;
while($zonecoorinate = $zonecoorinates->fetch_assoc()){
	$coordinate = $zonecoorinate['zone_coordinates'];
	$coordinate = explode(" ",$coordinate);
	$zone_coors[$count]['coor'] = $coordinate;
	$zone_coors[$count]['zones'] = $zonecoorinate['zone_id'];
	//$zone_coors[] = $coordinate;
	$count++;	

}
$fzone_coor = array();
$b = 0;
foreach($zone_coors as $key => $zone_coor) {
	$a = 0;
	foreach($zone_coor['coor'] as $zone){
		$explode_zone = explode(",",$zone);		
		$fzone_coor[$key]['coordy'][$a] = $explode_zone[0];
		$fzone_coor[$key]['coordx'][$a] = $explode_zone[1];
		$fzone_coor[$key]['zonename'][$a] = $zone_coor['zones'];
		$a++;	
	}
	$b++;		
}

$customerzone = array();
$delivery_postal_code = $post['userpostalcode'];
$pccoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($delivery_postal_code) . '&sensor=true&key='.API_KEY);
$pccoordinates = json_decode($pccoordinates);
$pclongitude_x  = $pccoordinates->results[0]->geometry->location->lat;
$pclatitude_y = $pccoordinates->results[0]->geometry->location->lng;

	foreach($fzone_coor as $indx=>$fzone_coordinate) {	
				$zonetoarr = array_unique($fzone_coordinate['zonename']);
				//print_r($zonetoarr);
				$tovertices_x = $fzone_coordinate['coordx'];
				$tovertices_y = $fzone_coordinate['coordy'];
				$topoints_polygon = count($tovertices_x) - 1; 
				$pointLocation = new pointLocation();
				$polygon = $zone_coors[$indx]['coor'];
				//echo "<br>ELSE checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
				//print_r($polygon);
				if($pointLocation->pointInPolygon($pclatitude_y.",".$pclongitude_x, $polygon)=='inside')
				{
						 $customerzone[] = $zonetoarr[0];
						
				}
			} 
//print_r($customerzone);
$custzones = array_unique($customerzone);		
$cart_products = $post['productids[]'];
$delivery_time = $post['deliverytime'];
$fdelivery_time =  date('h:i a', strtotime($delivery_time));
$delivery_date = $post['deliverydate'];
$products_table = "products";	
$stores_table = "stores";
$zone_table = "zone_detail";
$products_count = count(array_unique($cart_products));
$uniquestore = array();
$limitzones = array();
foreach($cart_products as $cart_product) {
	$products_sql = "select * from `".$products_table."` where `product_id`='".$cart_product."'";
	$product_records = $conn->query($products_sql) or die(mysql_error()); 
	if ( $product_records->num_rows > 0 ) {
		while($product_record = $product_records->fetch_assoc()) {
			$product_name = $product_record['product_name'];
			$storelist = $product_record['product_store_list'];
			if(empty($storelist)) {
				$store_postalcodes[] = '000000';
			}
			$storelistarr = array();
			$storelistarr = unserialize($storelist);
			$storelistarr = array_unique($storelistarr);
			foreach($storelistarr as $storeitem) {
				if(!in_array($storeitem,$uniquestore)){
					
				
				$stores_sql = "select store_postalcode from ".$stores_table." where store_id='".$storeitem."'";
				$store_records = $conn->query($stores_sql) or die(mysql_error()); 
				if ( $store_records->num_rows > 0 ) {
					while($store_record = $store_records->fetch_assoc()) {
						$store_postalcodes[] = $store_record['store_postalcode'];
												
					}	
				}	
				$uniquestore[] = $storeitem;
				}
				}
			}
		}	
}
$limitzonequery = "Select * from zones limit 5";
$limitrecords = $conn->query($limitzonequery);
while($limitrecord = $limitrecords->fetch_assoc()){
	$limitzones[] = $limitrecord['zone_id'];
}
$stores = array_unique($store_postalcodes);
$zoneregprice = array();
$zonehotprice = array();
$zoneurgentprice = array();
$zoneregname = array();
$uniquezone = array();
foreach ($stores as $store_postalcode){
	
	$edcoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($store_postalcode) . '&sensor=true&key='.API_KEY);
	
	$edcoordinates = json_decode($edcoordinates);
	
	$longitude_x  = $edcoordinates->results[0]->geometry->location->lat;
	$latitude_y = $edcoordinates->results[0]->geometry->location->lng; 
	
	$z = 0;	
	 foreach($fzone_coor as $indx=>$fzone_coordinate) {	
				$zonearr = array_unique($fzone_coordinate['zonename']);
				$vertices_x = $fzone_coordinate['coordx'];
				$vertices_y = $fzone_coordinate['coordy'];
				$points_polygon = count($vertices_x) - 1; 
				//if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){	
					$pointLocation = new pointLocation();
					$polygon =$zone_coors[$indx]['coor'];
					//echo "<br>checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
					//print_r($polygon);
					if($pointLocation->pointInPolygon($latitude_y.",".$longitude_x, $polygon)=='inside')
					{
						//print_r($custzones);
						foreach($custzones as $czone){
							
						if(in_array($zonearr[0],$limitzones)){
						   if(!in_array($zonearr[0],$uniquezone)){	
							$zonesql = "select * from `".$zone_table."` where `zone_from`='".$zonearr[0]."' and `zone_to`='".$czone."'";
							//echo $zonesql;
						$zone_records = $conn->query($zonesql) or die(mysql_error()); 
						if ( $zone_records->num_rows > 0 ) 
						{
							while($zone_record = $zone_records->fetch_assoc()) 
							{
								
									$zonename = $zone_record['zone_from'];
								    
									$zonerprice = $zone_record['price_regular'];
									$zonehprice = $zone_record['price_hot'];
									$zoneuprice = $zone_record['price_urgent'];
									//$chargesarray['storezipcode'] = $store_postalcode;
									//$chargesarray['deliveryprice'] = $zoneprice;
									//$chargesarray['urgentdeliveryprice'] = $zoneurgentprice;
									$zoneregprice[] = $zonerprice;
									$zonehotprice[] = $zonehprice;
									$zoneurgentprice[] = $zoneuprice;
									$zoneregname[] = $zonename;
								
							}
						}	
						$uniquezone[] = $zonearr[0];
						}	
						}
						}
					}	
					//}	
			}
}
/*print_r($store_postalcodes);*/
/*$z = 0;
foreach($zone_coordinates['allzones']['coords'] as $fzone_coordinate) {
	
	foreach($fzone_coordinate as $fzone_coord) {
		$fzone_coordr = explode(",",$fzone_coord);
		$vertices_x[$z] = $fzone_coordr[0];
		$vertices_y[$z] = $fzone_coordr[1];
		$z++;
	}
}
$z = 0;
foreach($fzone_coordinates as $fszone_coordinate) {
	$finalArr[$z] = $fszone_coordinate; 
	$z++;
}*/
//print_r($zoneregname);
$rprice[] = array_sum($zoneregprice);
$hprice[] = array_sum($zonehotprice);
$uprice[] = array_sum($zoneurgentprice);
//$rprice[] = $zoneregprice[0];
//$hprice[] = $zonehotprice[0];
//$uprice[] = $zoneurgentprice[0];
if(!empty($store_postalcodes)) {
	$response['status'] = 1;
	$response['store_postalcodes'] = $store_postalcodes;
	$response['zone_name'] = $zoneregname;
	$response['regularprice'] = $rprice;
	$response['hotprice'] = $hprice;
	$response['urgentprice'] = $uprice;
	$response['products_count'] = $products_count;
	$response['delivery_date'] = $delivery_date;
	$response['delivery_time'] = $delivery_time;
} else {
	$response['status'] = 2;
}	

echo json_encode($response);	
die(); 
?>