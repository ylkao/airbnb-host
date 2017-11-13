<?php

/** 
  * Convert data from a JSON file into a PHP object
  * @param filename the name of the file
  * @return the JSON info as a PHP object
*/
function getData($filename) {
  $data = file_get_contents($filename); 

  //Fix JSON syntax error with decoding: https://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok
  for ($i = 0; $i <= 31; ++$i) { 
      $data = str_replace(chr($i), "", $data); 
  }
  $data = str_replace(chr(127), "", $data);

  // This is the most common part
  // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
  // here we detect it and we remove it, basically it's the first 3 characters 
    if (0 === strpos(bin2hex($data), 'efbbbf')) {
       $data = substr($data, 3);
    }

    $data = json_decode($data);
    return $data;

}

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula. Credits: https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius =6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

/**
* Get the nearest locations to a given latitude and longitude
* @param $lat1 the latitude
* @param $lon1 the longitude
* @param $listings an array of listings
* @param $size the number of nearest locations to get
* @return an array of the nearest locations
*/
function nearestLocations($lat1, $lon1, $listings, $size) { 
  //array of closest objects
  $closest = [];
  for ($i = 0; $i < count($listings); $i++) {
    $lat = $listings[$i]->latitude;
    $lon = $listings[$i]->longitude;
    $dist = haversineGreatCircleDistance($lat1, $lon1, $lat, $lon);
    if (count($closest) < $size and $dist != 0) {
      $closest[$dist] = $listings[$i];
    } else if ($dist != 0) {
      $closest = compareDist($closest, $dist, $listings[$i]);
    }
  }
  return $closest;
}

/**
 * Destructively modify the closest array to remove/replace the current farthest location in the array with a nearer one
 * @param $closest the current location you are comparing
 * @param $dist the distance between closest and listing
 * @param $listing the location you are comparing against closest
 * @return the modified array of closest locations
*/
function compareDist($closest, $dist, $listing) {
  $maxDist = max(array_keys($closest)); //get farthest city from closest
  if ($dist < $maxDist) {
    unset($closest[$maxDist]); //remove and replace with the closer city
    $closest[$dist] = $listing;
  }
  return $closest;
}

/**
  * Calculate the estimated weekly income for a list of listings based off price and number of bookings (= number of reviews)
  * @param $listings the array of listings
  * @return the average weekly income of the listings
*/
function estimateWeekly($listings) {
  $sum = 0.0;
  $total = count($listings);
  foreach ($listings as $key=> $val) {
    //NOTE: equate reviews_per_month to bookings per month, divide by 4 to get bookings per week 
    //sum listings that have been "booked"
    if ($val->reviews_per_month) {
      $weekly = dollarToNumber($val->price) * ($val->reviews_per_month / 4.0); 
      $sum += $weekly;
    }
    
  }
  $formatted = round($sum / $total, 2);
  return number_format($formatted, 2); //keep trailing 0s

}


/** 
* Strip dollar signs and convert dollar string to a number 
* @param $dollar the dollar to convert
* @return the number value of the dollar string
*/
function dollarToNumber($dollar) {
  return floatval(ltrim($dollar, '$'));
}

/**
 * Calculate the average weekly income of a given location based off of user input from index.php
*/
function main() {
  try {
    //get user input
    $lat = htmlspecialchars($_GET["lat"]);
    $lon = htmlspecialchars(($_GET["lon"]));

    //make sure latitude and longitude are valid
    if (is_numeric($lat) && is_numeric($lon)) {
      $listings = getData("data//listings_all.json");
      $closest = nearestLocations($lat, $lon, $listings, 50);
      $price = estimateWeekly($closest);
      echo "$".$price;
    } else {
      echo "Please make sure your input is a valid geo-location.";
    }
  } catch (Exception $e) {
    echo "Please make sure your input is a valid geo-location.";
  }
  
}

main();

?>