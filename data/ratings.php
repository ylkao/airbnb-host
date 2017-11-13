<?php

/**
 * Encode PHP array into JSON and store it in a separate file for later use
 * @param $file the name of the file to hold the array
 * @param $arr the array to be stored
*/
function store_array($file, $arr) {
  file_put_contents($file, json_encode($arr));
}

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

  // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
  // here we detect it and we remove it, basically it's the first 3 characters 
    if (0 === strpos(bin2hex($data), 'efbbbf')) {
       $data = substr($data, 3);
    }

    $data = json_decode($data);
    return $data;
}

/**
 * Iterate through listings and find the average rating for each zip code
 * @param $listings the array of listings to look through
 * @return an array of zip codes mapped to their average rating
*/
function zip_to_rating($listings) {
	//create assoc array of zipcodes mapped to an array of all the ratings in that zip code
	$zip = []; 
	for ($i = 0; $i < count($listings); $i++) {
		$zipcode = $listings[$i]->zipcode;
		$rating = $listings[$i]->review_scores_rating;
		if ($rating != null && strcmp($zipcode, "") != 0) {
			if (!isset($zip[$zipcode])) {
				$zip[$zipcode] = [$rating];
			} else if (isset($zip[$zipcode])) {
				array_push($zip[$zipcode], $rating);
			}
		}
		
	}

	//average the ratings per zip code
	$result = [];
	foreach ($zip as $key => $value) {
		$result[$key] = avgArray($value);
  }

  //sort the zip codes by descending ratings
  arsort($result);
  return $result;

}

/**
 * Average the values in an array
 * @param $arr the array of values to average
 * @return the average value of the array
*/
function avgArray($arr) {
	if (count($arr) == 0) {
		return 0;
	} else {
		return array_sum($arr) / count($arr);
	}
}

//get all the listings
$listings = getData("listings_all.json");

//get the average rating per zipcode and store this for later use
$zip_rate = zip_to_rating($listings);
store_array("ratings.json", $zip_rate);

?>