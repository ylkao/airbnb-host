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
 * Return array of array pairs for the pie chart with pair: name of amenity to frequency. Only include amenities with over 500 listings/top 10 most frequent amenities
 * @param $listings the Airbnb listings
 * @param $x the x-axis title 
 * @param $y the y-axis title
 * @return the array of pairs for the pie chart
*/
function createPie($listings, $x, $y) {
  $result = [];
  array_push($result, [$x, $y]);
  $amenities = createCounts($listings); //amenities sorted from highest to lowest frequency
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
  * Count the frequency of amenities
  * @param $listings the Airbnb listings to iterate through
  * @return an array of amenities mapped to their frequencies from greatest to least frequent
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
  * Turn string of words split by commas into an array of words
  * @param $str the string that is being turned into an array
  * @return the array of words
*/
function strToArray($str) {
  //remove curly braces and quotes
  $cleaned = str_replace("{", "", $str);
  $cleaned = str_replace("}", "", $cleaned);
  $cleaned = str_replace('"', "", $cleaned);
  return explode(",", $cleaned);
}

//get list of Airbnb listings
$data = getData("listings_all.json"); 
//create data for the pie chart
$amenities = createPie($data, "Amenities", "Number of Listings");
store_array("amenities.json", $amenities);

?>


<html>
  <head>
  <title> Pie Chart </title>
  </head>


  <body>

<!-- Test Pie chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      var pie = <?php echo json_encode($amenities); ?>;
      function drawChart() {
        var data = google.visualization.arrayToDataTable(pie);

        var options = {
          title: 'Available Amenities',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>

</script>

  </body>
</html>