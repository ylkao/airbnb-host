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
* Strip dollar signs and convert dollar string to a number 
* @param $dollar the dollar to convert
* @return the number value of the dollar string
*/
function dollarToNumber($dollar) {
  return floatval(ltrim($dollar, '$'));
}

/**
 * Return array of array pairs, with pair: number of people it accommodates and price
 * @param $listings array of Airbnb listings
 * @param $x the x-axis title 
 * @param $y the y-axis title
 * @return the array of pairs for the scatter plot
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

//get all the Airbnb listings
$data = getData("listings_all.json"); 

//create data for the scatter plot and store it for later use
$scatter = createScatter($data, "Number of People Accommodated", "Price");
store_array("price_people.json", $scatter);

?>


<html>
  <head>
  <title> Price vs. Number of People Accommodated </title>
  </head>

  <body>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      var scatter = <?php echo json_encode($scatter); ?>;
      
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(scatter);

        var options = {
          title: 'Number of People Accommodated vs. Price comparison',
          hAxis: {title: 'Number of People Accomodated', minValue: 0, maxValue: 17},
          vAxis: {title: 'Price in Dollars', minValue: 0, maxValue: 1100},
          legend: 'none',
          colors: ["#FF5A5F"]
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 900px; height: 700px;"></div>

  </body>
</html>