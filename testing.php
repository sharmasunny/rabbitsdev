<?php
include('constants.php');
include('db.php');
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
$products_table = "products";	
$stores_table = "stores";
$data = $_POST['fdata'];

foreach($_POST['fdata'] as $key=>$value)
{
	$post[$value['name']] = $value['value'];	
}
$delivery_postal_code = $post['store_prod_finder'];
$collection = $post['store_collection_finder'];
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

$z = 0;
$zonearr = array();
$zonelist = array();

foreach($fzone_coor as $indx=>$fzone_coordinate) {
	
	$zonearr = array_unique($fzone_coordinate['zonename']);
	$vertices_x = $fzone_coordinate['coordx'];
	$vertices_y = $fzone_coordinate['coordy'];
	$points_polygon = count($vertices_x) - 1; 
	if(!empty($_POST)) {
		$postalcode = $delivery_postal_code;
		$edcoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalcode) . '&sensor=true&key='. API_KEY);
	
		$edcoordinates = json_decode($edcoordinates);
		$longitude_x  = $edcoordinates->results[0]->geometry->location->lat;
		$latitude_y = $edcoordinates->results[0]->geometry->location->lng;
	} else {
		$longitude_x = ""; 
		$latitude_y = ""; 
	}
	$pointLocation = new pointLocation();
	$polygon = $zone_coors[$indx]['coor'];
	echo "<br>ELSE checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
	print_r($polygon);
	if($pointLocation->pointInPolygon($pclatitude_y.",".$pclongitude_x, $polygon)=='inside')
	{
	  	$zonelist[] = $zonearr[0];
	} 
}
$newcollection = $collection;

$uniquecollectionstore = array();
$uniquestore = array();  
if(isset($newcollection) && $newcollection != ""){
	
	$collection_sql = "select * from collections where collection_handle='".$newcollection."'";
$collection_records = $conn->query($collection_sql);
while($collection_record = $collection_records->fetch_assoc()){
	$uniquecollectionstore = unserialize($collection_record['collection_store_list']);
	//print_r($uniquecollectionstore);
	/*$stores_sql = "select * from ".$stores_table." where store_collection_list REGEXP '.*s:[0-9]+:".$collection_record['id'].".*' order by store_id DESC";
$stores_records = $conn->query($stores_sql);
while($stores_record = $stores_records->fetch_assoc()) {
		//echo $stores_record['store_id'];
		$uniquecollectionstore[] = $stores_record['store_id'];
	}*/
}
//print_r($uniquecollectionstore);
$products_sql = "select * from ".$products_table." order by id DESC";
$products_records = $conn->query($products_sql);
$stores_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
$stores_records = $conn->query($stores_sql);
$s = 0;
$st = 0;
while($stores_record = $stores_records->fetch_assoc()) {
	while($products_record = $products_records->fetch_assoc()) {
		$snames 		  = "";
		$store_names 	  = array();
		$id        		  = $products_record['id'];
		$productid        = $products_record['product_id'];
		$productname 	  = $products_record['product_name'];
		$productstorelist = $products_record['product_store_list'];
		$productstorelistarr = array();
		$productstorelistarr = unserialize($productstorelist);
		if (is_array($productstorelistarr) || is_object($productstorelistarr))
		{
			$store_ids = array();
			$prod_ids = array();
			$finalstorearr['stores'] = array();
		    foreach($productstorelistarr as $storeid) {
				$stores_sql    = "select * from ".$stores_table." where store_id = ".$storeid." and store_is_deleted='0' ";
				$store_records = $conn->query($stores_sql);
				if ( $store_records->num_rows > 0 ) {
					while($store_record = $store_records->fetch_assoc()) {
						$prod_ids[] = $productid;
						$store_ids[] = $store_record['store_id'];
					}	
				} else {
					$store_ids = array();
				}
			}
		} else {
			 $store_ids = array();
		}
		if(!empty($store_ids)) {
			$storearr[$s]['products'] = $productid;
			$storearr[$s]['stores'] = $store_ids;
			$s++;
		} 
	}
	$k = 0;
	foreach($storearr as $storeitem) {
		foreach($storeitem['stores'] as $storearitem) {
			$mainarr[$storearitem][$k] = $storeitem['products'];
			$k++;
		}
	}
	$store_id = $stores_record['store_id'];
	$store_id;
	$storeaddr 	  = $stores_record['store_address'];
	$storecity 	  = $stores_record['store_city'];
	$longitude_x  = $stores_record['store_latitude'];
	$latitude_y   = $stores_record['store_longitude'];
	 foreach($fzone_coor as $indx=>$fzone_coordinate) {					
		$zonearr = array_unique($fzone_coordinate['zonename']);
		$vertices_x = $fzone_coordinate['coordx'];
		$vertices_y = $fzone_coordinate['coordy'];
		
		$points_polygon = count($vertices_x) - 1; 
		$pointLocation = new pointLocation();
	$polygon = $zone_coors[$indx]['coor'];
	//echo "<br>ELSE checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
	//print_r($polygon);
	if($pointLocation->pointInPolygon($pclatitude_y.",".$pclongitude_x, $polygon)=='inside')
	{
		//foreach($zonelist as $zoneitem){
		//$zonequery = "SELECT * FROM distance where distance != '' and zone_one = '".trim($zoneitem)."' ORDER BY CAST( distance AS DECIMAL( 10, 5 ) ) ASC";
		
		
		//$zonerecords = $conn->query($zonequery);
	//while($zonedata = $zonerecords->fetch_assoc()){
		
				$storedistance = $zonedata['distance'];
			
				$st_name = $stores_record['store_name'];
				$st_addr = $stores_record['store_address'].",".$stores_record['store_city'].",".$stores_record['store_state'].",".$stores_record['store_country']."-".$stores_record['store_postalcode'];
				
				foreach($mainarr as $key => $value) {
					$storeid = $key;
					if($store_id == $storeid )
					{
						if(in_array($storeid,$uniquecollectionstore)){
								if(!in_array($storeid,$uniquestore)){
							$st_sql = "select * from ".$stores_table." where store_id=".$storeid." and store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
							$st_records = $conn->query($st_sql);
							while($st_record = $st_records->fetch_assoc()) 														{
								$zonenamesql = "Select * from `zones` where zone_id = '".$st_record['store_zone_id']."'";
							$zonenamerecords = $conn->query($zonenamesql);
							$zonenamerecord = $zonenamerecords->fetch_assoc();
							$query = "SELECT * FROM `distance` where zone_one = '".trim($zoneitem)."' and zone_two ='".$zonenamerecord['zone_name']."' ORDER BY CAST( distance AS DECIMAL( 10, 5 ) ) ASC ";
							//echo $query;
							$distance_records = $conn->query($query);
							$distance_record = $distance_records->fetch_assoc();
							$query1 = "SELECT MIN(price_regular) as price FROM zone_detail where zone_from = '".$st_record['store_zone_id']."'";
							$price_records = $conn->query($query1);
							$price_record = $price_records->fetch_assoc();
						$st_name = $st_record['store_name'];
						$st_addr = $st_record['store_address'].",".$st_record['store_city'].",".$st_record['store_state'].",".$st_record['store_country']."-".$st_record['store_postalcode'];
						$store_zone = unserialize($st_record['store_name']);
						$response['storearr'][$st]['stname'][] = $st_name; 
						$response['storearr'][$st]['staddr'][] = $st_addr;
						$response['storearr'][$st]['stdistance'][] = $distance_record['distance'];
						$response['storearr'][$st]['minimumcharge'][] = $price_record['price'];
						$response['storearr'][$st]['stdistance'][] = $storedistance;
						$response['storeproddetails'] .= "<div class='store-details'><div class='heading-panel'><h3 class='hp-logo'>".$st_name."</h3>"; 
						$response['storeproddetails'] .= "<div class='tags'><span> $".$price_record['price']." </span><span>delivery price</span></div></div>";
						 
					}	
							$prodlists = $value;
							$newprodlists = array();
							foreach($prodlists as $prodlist) 		{
							$newprodlists[] = $prodlist;
						}
							$i=0;
							foreach($newprodlists as $newprodlist) 															{
							$pd_sql = "select * from ".$products_table." where product_id=".$newprodlist."";
							$pd_records = $conn->query($pd_sql);
							while($pd_record = $pd_records->fetch_assoc()) {
								$prodid = $pd_record['product_id'];
								$prodname = $pd_record['product_name'];
								$prodimg = $pd_record['product_img'];
								$prodprice = $pd_record['product_price'];
								$response['prodarr'][$st][] = $prodid; 
								$response['storearr'][$st]['products'][] = $prodid;
								$response['storeproddetails'] .= "<div class='item'><a href='https://24sevens.myshopify.com/pages/store-products?store=".$storeid."'><img src=".$prodimg." title=".$prodname." height='150px' width='150px'/></a>";
								$response['storeproddetails'] .= "<h5>".$prodname."</h5></div>";
							}
							$i++;
							if($i==4) break;
						}
							$response['storeproddetails'].= "</div>";
							$st++;
							$uniquestore[] = $storeid;
							}
						}
						}
				}
		
			//}
		//}
	}	  	
		}
	}
} else {

$products_sql = "select * from ".$products_table." order by id DESC";
$products_records = $conn->query($products_sql);
$stores_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
$stores_records = $conn->query($stores_sql);
$s = 0;
$st = 0;
while($stores_record = $stores_records->fetch_assoc()) {
	while($products_record = $products_records->fetch_assoc()) {
		$snames 		  = "";
		$store_names 	  = array();
		$id        		  = $products_record['id'];
		$productid        = $products_record['product_id'];
		$productname 	  = $products_record['product_name'];
		$productstorelist = $products_record['product_store_list'];
		$productstorelistarr = array();
		$productstorelistarr = unserialize($productstorelist);
		if (is_array($productstorelistarr) || is_object($productstorelistarr))
		{
			$store_ids = array();
			$prod_ids = array();
			$finalstorearr['stores'] = array();
		    foreach($productstorelistarr as $storeid) {
				$stores_sql    = "select * from ".$stores_table." where store_id = ".$storeid." and store_is_deleted='0' ";
				$store_records = $conn->query($stores_sql);
				if ( $store_records->num_rows > 0 ) {
					while($store_record = $store_records->fetch_assoc()) {
						$prod_ids[] = $productid;
						$store_ids[] = $store_record['store_id'];
					}	
				} else {
					$store_ids = array();
				}
			}
		} else {
			 $store_ids = array();
		}
		if(!empty($store_ids)) {
			$storearr[$s]['products'] = $productid;
			$storearr[$s]['stores'] = $store_ids;
			$s++;
		} 
	}
	$k = 0;
	foreach($storearr as $storeitem) {
		foreach($storeitem['stores'] as $storearitem) {
			$mainarr[$storearitem][$k] = $storeitem['products'];
			$k++;
		}
	}
	$store_id = $stores_record['store_id'];
	$store_id;
	$storeaddr 	  = $stores_record['store_address'];
	$storecity 	  = $stores_record['store_city'];
	$longitude_x  = $stores_record['store_latitude'];
	$latitude_y   = $stores_record['store_longitude'];
	 foreach($fzone_coor as $indx=>$fzone_coordinate) {					
		$zonearr = array_unique($fzone_coordinate['zonename']);
		$vertices_x = $fzone_coordinate['coordx'];
		$vertices_y = $fzone_coordinate['coordy'];
		
		$points_polygon = count($vertices_x) - 1; 
		$pointLocation = new pointLocation();
				$polygon = $zone_coors[$indx]['coor'];
				//echo "<br>ELSE checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
				//print_r($polygon);
				if($pointLocation->pointInPolygon($pclatitude_y.",".$pclongitude_x, $polygon)=='inside')
				{
		//foreach($zonelist as $zoneitem){
		//$zonequery = "SELECT * FROM distance where distance != '' and zone_one = '".trim($zoneitem)."' ORDER BY CAST( distance AS DECIMAL( 10, 5 ) ) ASC";
		
		
		//$zonerecords = $conn->query($zonequery);
	//while($zonedata = $zonerecords->fetch_assoc()){
		
				$storedistance = $zonedata['distance'];
			
				$st_name = $stores_record['store_name'];
				$st_addr = $stores_record['store_address'].",".$stores_record['store_city'].",".$stores_record['store_state'].",".$stores_record['store_country']."-".$stores_record['store_postalcode'];
				
				foreach($mainarr as $key => $value) {
					$storeid = $key;
					if($store_id == $storeid )
					{	
					
					if(!in_array($storeid,$uniquestore)){
							$st_sql = "select * from ".$stores_table." where store_id=".$storeid." and store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
							$st_records = $conn->query($st_sql);
							while($st_record = $st_records->fetch_assoc()) 														{
							$zonenamesql = "Select * from `zones` where zone_id = '".$st_record['store_zone_id']."'";
							$zonenamerecords = $conn->query($zonenamesql);
							$zonenamerecord = $zonenamerecords->fetch_assoc();
							$query = "SELECT * FROM `distance` where zone_one = '".trim($zoneitem)."' and zone_two ='".$zonenamerecord['zone_name']."' ORDER BY CAST( distance AS DECIMAL( 10, 5 ) ) ASC ";
							//echo $query;
							$distance_records = $conn->query($query);
							$distance_record = $distance_records->fetch_assoc();
							$query1 = "SELECT MIN(price_regular) as price FROM `zone_detail` where zone_from = '".$st_record['store_zone_id']."'";
							//echo $query1;
							$price_records = $conn->query($query1);
							$price_record = $price_records->fetch_assoc();
						$st_name = $st_record['store_name'];
						$st_addr = $st_record['store_address'].",".$st_record['store_city'].",".$st_record['store_state'].",".$st_record['store_country']."-".$st_record['store_postalcode'];
						
						$response['storearr'][$st]['stname'][] = $st_name; 
						$response['storearr'][$st]['staddr'][] = $st_addr;
						$response['storearr'][$st]['stdistance'][] = $distance_record['distance'];
						$response['storearr'][$st]['minimumcharge'][] = $price_record['price'];
						$response['storeproddetails'] .= "<div class='store-details'><div class='heading-panel'><h3 class='hp-logo'>".$st_name."</h3>"; 
						$response['storeproddetails'] .= "<div class='tags'><span> $".$price_record['price']." </span><span>delivery price</span></div></div>"; 
						 
					}	
							$prodlists = $value;
							$newprodlists = array();
							foreach($prodlists as $prodlist) 		{
							$newprodlists[] = $prodlist;
						}
							$i=0;
							foreach($newprodlists as $newprodlist) 															{
							$pd_sql = "select * from ".$products_table." where product_id=".$newprodlist."";
							$pd_records = $conn->query($pd_sql);
							while($pd_record = $pd_records->fetch_assoc()) {
								$prodid = $pd_record['product_id'];
								$prodname = $pd_record['product_name'];
								$prodimg = $pd_record['product_img'];
								$prodprice = $pd_record['product_price'];
								$response['prodarr'][$st][] = $prodid; 
								$response['storearr'][$st]['products'][] = $prodid;
								$response['storeproddetails'] .= "<div class='item'><a href='https://24sevens.myshopify.com/pages/store-products?store=".$storeid."'><img src=".$prodimg." title=".$prodname." height='150px' width='150px'/></a>";
								$response['storeproddetails'] .= "<h5>".$prodname."</h5></div>";
							}
							$i++;
							if($i==4) break;
						}
							$response['storeproddetails'].= "</div>";
							$st++;
							$uniquestore[] = $storeid;
						}
						}
				}
		
			//}
		//}
	}	  	
		}
	}
}

function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = 0;
  for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
    if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
     ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
       $c = !$c;
  }
  return $c;
}
if($response != "") {
	$response['status'] = 1;
	echo json_encode($response);
} else {
	$response['status'] = 2;
	echo json_encode($response);
}
	
die(); 
?>