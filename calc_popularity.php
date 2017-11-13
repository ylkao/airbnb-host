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

    $data = json_decode($data, true);
    return $data;

}

/**
 * Get the average rating of a given zipcode based on user input from index.php
*/
function main() {
	$ratings = getData("data//ratings.json");
	$zipcode = htmlspecialchars($_GET["zipcode"]);
	if (!isset($ratings[$zipcode])) {
		echo "Please enter a valid zipcode in the San Francisco area.";
	} else {
		echo round($ratings[$zipcode], 2);
	}
}

main();

?>