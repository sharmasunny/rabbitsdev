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


$size = 'medium'; 
$delivery_class = 'urgent';
$pickup = 'V7H 2Y9';
$dropoff = 'V7H 2Y9';
$date = $_REQUEST['date'];
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
//echo "<pre>";

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
//echo "<hr>";
//print_r($fzone_coor);
$z = 0;
$zonearr1 = array();
$zonelist1 = array();
foreach($fzone_coor as $indx=>$fzone_coordinate) {
	
	$zonearr1 = array_unique($fzone_coordinate['zonename']);
	$vertices_x = $fzone_coordinate['coordx'];
	$vertices_y = $fzone_coordinate['coordy'];
	$points_polygon = count($vertices_x) - 1; 
	if(!empty($_REQUEST)) {
		echo "heloo";
		$postalcode = $pickup;
		$edcoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalcode) . '&sensor=true&key='. API_KEY);
		echo 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalcode) . '&sensor=true&key='. API_KEY;
		
		$edcoordinates = json_decode($edcoordinates);
		$longitude_x  = $edcoordinates->results[0]->geometry->location->lat;
		$latitude_y = $edcoordinates->results[0]->geometry->location->lng;
	} else {
		$longitude_x = ""; 
		$latitude_y = ""; 
	}
	
	if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
	  
		
		$pointLocation = new pointLocation();
		$polygon =$zone_coors[$indx]['coor'];
		//echo "<br>checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
		//print_r($polygon);
		if($pointLocation->pointInPolygon($latitude_y.",".$longitude_x, $polygon)=='inside')
		{
			$zonelist1[] = $zonearr1[0];
		}
	} 
}
//print_r($zonelist1);

$zonearr2 = array();
$zonelist2 = array();
foreach($fzone_coor as $indx=>$fzone_coordinate) {
	
	$zonearr2 = array_unique($fzone_coordinate['zonename']);
	$vertices_x = $fzone_coordinate['coordx'];
	$vertices_y = $fzone_coordinate['coordy'];
	$points_polygon = count($vertices_x) - 1; 
	if(!empty($_REQUEST)) {
		$postalcode = $dropoff;
		$edcoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalcode) . '&sensor=true&key='. API_KEY);
		
		$edcoordinates = json_decode($edcoordinates);
		$longitude_x  = $edcoordinates->results[0]->geometry->location->lat;
		$latitude_y = $edcoordinates->results[0]->geometry->location->lng;
	} else {
		$longitude_x = ""; 
		$latitude_y = ""; 
	}
	
	if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
	  	$pointLocation = new pointLocation();
		$polygon =$zone_coors[$indx]['coor'];
		//echo "<br>checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
		//print_r($polygon);
		if($pointLocation->pointInPolygon($latitude_y.",".$longitude_x, $polygon)=='inside')
		{
			$zonelist2[] = $zonearr2[0];
		}
	} 
}

//print_r($zonelist2);
$zonerprice = array();
$zonehprice = array();
$zoneuprice = array();
if($size == "medium"){
	$zonetable = "zone_detail";
} else if($size == "large") {
	$zonetable = "large_zone_detail";
}
	
	//print_r($zonelist1);
	//print_r($zonelist2);
	foreach($zonelist1 as $zonefrom){
		//echo $zonefrom;
		foreach($zonelist2 as $zoneto){
			//echo $zoneto;
		
			$zoneprice = "Select * from `".$zonetable."` where zone_from = '".$zonefrom."' and zone_to = '".$zoneto."'";
			print_r($zoneprice);
			//echo $zoneprice; 
			$pricerecords = $conn->query($zoneprice); 
			while($pricerecord = $pricerecords->fetch_assoc()){
				$rprice = $pricerecord['price_regular'];
				$hprice = $pricerecord['price_hot'];
				$uprice = $pricerecord['price_urgent'];
				$zonerprice = $rprice;
				$zonehprice = $hprice;
				$zoneurgentprice = $uprice;
			}
		}
	}
	

$response['status'] = 1;
$response['pickup'] = $pickup;
$response['dropoff'] = $dropoff;
$response['delivery'] = $delivery_class;
$response['date'] = $date;
$response['regularprice'] = $zonerprice;
$response['hotprice'] = $zonehprice;
$response['urgentprice'] = $zoneurgentprice;
//echo json_encode($response);
//die();
?>