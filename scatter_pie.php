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

  // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
  // here we detect it and we remove it, basically it's the first 3 characters 
    if (0 === strpos(bin2hex($data), 'efbbbf')) {
       $data = substr($data, 3);
    }

    $data = json_decode($data);
    return $data;

}

/** 
 *Convert dollar string to numeric value 
 * @param $dollar the string to be converted
 * @return the numeric value of the string
*/
function dollarToNumber($dollar) {
  return floatval(ltrim($dollar, '$'));
}

/**
 * Return array of array pairs, with pair: number of people it accommodates and price to create the scater plot
 * @param $listings the array of listings to add to the array
 * @param $x the title of the x-axis
 * @param $y the title of the y-axis
 * @return an array of array pairs to create the scatter plot
*/
function createScatter($listings, $x, $y) {
  $result = [];
  array_push($result, [$x, $y]);
  for ($i = 0; $i < count($listings); $i++) {
    $pair = array($listings[$i]->accommodates, dollarToNumber($listings[$i]->price));
    array_push($result, $pair);
  }
  return $result;
}

/**
 * Return array of array pairs, with pair: amenity and frequency
 * @param $listings the array of listings to add to the array
 * @param $x the title of the amenity
 * @param $y the title of the frequency
 * @return an array of array pairs to create the pie chart
*/
function createPie($listings, $x, $y) {
  $result = [];
  array_push($result, [$x, $y]);
  $amenities = createCounts($listings);
  foreach ($amenities as $key => $value) {
    if (count($result) > 10) {
      break;
    }
    //only show amenities that over 500 listings have and remove the "translation missing: en.hosting_amenity_50", "translation missing: en.hosting_amenity_49" value
    if ($value >= 500 && strcmp($key, "translation missing: en.hosting_amenity_50") != 0 && strcmp($key, "translation missing: en.hosting_amenity_49") != 0)
    array_push($result, [$key, $value]);
  }
  return $result;

}

/**
 * Count the frequency of amenities given an array of listings
 * @param $listings the array of listings to iterate through
 * @return an associative array of amenities mapped to their frequencies
*/
function createCounts($listings) {
  $result = [];
  for ($i = 0; $i < count($listings); $i++) {
    $amenities = strToArray($listings[$i]->amenities);
    for ($j = 0; $j < count($amenities); $j++) {
      if (array_key_exists($amenities[$j], $result)) {
        $result[$amenities[$j]] += 1;
      } else {
        $result[$amenities[$j]] = 1;
      }
    }
  }
  //return array sorted from greatest to least
  arsort($result);
  return $result;
}

/**
* Clean the string by removing curly braces and quotes, then convert it to an array of words based on comma separation
* @param $str the string to be parsed
* @return array of words
*/
function strToArray($str) {
  //remove curly braces and quotes
  $cleaned = str_replace("{", "", $str);
  $cleaned = str_replace("}", "", $cleaned);
  $cleaned = str_replace('"', "", $cleaned);
  return explode(",", $cleaned);
}

/** 
* Store the array in a json file
* @param $file the name of the file
* @param $arr the array to be stored
*/
function store_array($file, $arr) {
  file_put_contents($file, json_encode($arr));
}

function main() {
  //get all the listings
  $data = getData("listings_all.json");

  //create data for visualizations
  $scatter = createScatter($data, "Number of People Accommodated", "Price");
  $amenities = createBar($data, "Amenities", "Number of Listings");

  //store data for later use
  store_array("amenities2.json", $amenities);
  store_array("scatter.json", $scatter);
}

//execute methods
//main();


?>