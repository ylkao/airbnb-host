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
 * Create array of property "values" mapped to the listing object, with value calculated based off time price and number of bookings (= number of reviews)
 * @param $listing the list of Airbnb listings
 * @return an associative array of property values with value listing object
*/
function listingToValue($listings) {
	$result = [];
	for ($i = 0; $i < count($listings); $i++) {
		$curr = $listings[$i];
		$value = (int) dollarToNumber($curr->price) * $curr->number_of_reviews;
		//break ties in value by keeping the listing with the higher rating
		if (array_key_exists($value, $result)) {
			if ($curr->review_scores_rating > $result[$value]->review_scores_rating) {
				$result[$value] = $curr;
			}
		} else {
			$result[$value] = $curr;
		}
	}
	krsort($result);
	return $result;
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
  * Calculate the estimated monthly income for a list of listings based off price and number of bookings (= number of reviews)
  * @param $listings the array of listings
  * @return the average monthly income of the listings
*/
function estimateMonthly($listings) {
  $sum = 0.0;
  $total = count($listings);
  foreach ($listings as $key=> $val) {
    //NOTE: equate reviews_per_month to bookings per month
    //sum listings that have been "booked"
    if ($val->reviews_per_month) {
      $monthly = dollarToNumber($val->price) * ($val->reviews_per_month); 
      $sum += $monthly;
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
 * Calculate the number of weeks it takes to break even based off a location and estimated monthly income, assuming you buy 100 properties in that area
 * @param $invested the amount of money you want to invest in an area
 * @param $listings the listings to iterate through
 * @param $lat the latitude of the location you want to invest in
 * @param $lon the longitude of the place you want to invest in
 * @return the number of weeks it takes to break even given your investment
*/
function breakEven($invested, $listings, $lat, $lon) {
	//estimate monthly income in that location
	$closest = nearestLocations($lat, $lon, $listings, 10);

	//http://www.businessinsider.com/how-expensive-is-san-francisco-2015-9/#for-a-family-of-four-expect-to-pay-about-91785-a-year-for-necessities--thats-7649-per-month-2
	//find number of properties that can be bought with the investment, median home price in SF is $841,600
	$properties = $invested / 841600;
	$monthly = estimateMonthly($closest) * $properties; 

	if ($monthly == 0) {
		return "No income is made.";
	} else {
		return (int) ($invested / $monthly);
	}
}

/**
 * Calculate the best place in SF to invest $100 million in
*/
function main() {
	$listings = getData("listings_all.json");
	$values = listingToValue($listings);

	//get listings with the top 10 "values"
	$count = 0;
	foreach ($values as $key => $val) {
		if ($count > 10) {
			break;
		}

		$lat = $val->latitude;
		$lon = $val->longitude;

		//calculate number of months it takes to break even
		$weeks = breakEven(100000000, $listings, $lat, $lon);

		//see results
		echo "Value: ".$key."<br>";
		echo "Location: ".$lat." ".$lon."<br>";
		echo "Neighbourhood: ".$val->neighbourhood."<br>";
		echo "Break even: ".$weeks."<br>";

		$count++;
	}

	
}

main();

?>