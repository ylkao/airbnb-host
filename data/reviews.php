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
 * Access txt file of reviews with small words like "I", "the", and "a" and irrelevant words like "San Francisco", "room", and "place" removed, return frequency of all words 
 * @return an array of words mapped to their frequency
*/

function countCleaned() {
  $txt = file_get_contents("reviews_clean.txt");
  $counts = [];
  $excluded = ["San", "Francisco", "The", "place", "room", "rooms", " ", "", "\r\n", "SF", "definitely", "all", "apartment", "host", "here", "-", "made", "Great"];
  //large file, read line by line
  $handle = fopen("reviews_clean.txt", "r");
  if ($handle) {
      while (($line = fgets($handle)) !== false) {
          $arr = explode(' ', $line);
          for ($i = 0; $i < count($arr); $i++) {
            if (!in_array($arr[$i], $excluded)) {
              if (!isset($counts[$arr[$i]])) {
                $counts[$arr[$i]] = 1;
              } else {
                $counts[$arr[$i]] += 1;
              }
            }
            
          }
      }
      fclose($handle);
  } else {
      echo "error";
  }

  arsort($counts);
  return $counts;
}

/**
 * Create array of pairs for the bar chart
 * @param $data the associative array to be converted into pairs
 * @param $x the x-axis title
 * @param $y the y-axis title
 * @return the array of pairs
*/
function createBar($data, $x, $y) {
  $result = [];
  array_push($result, [$x, $y]);
  foreach ($data as $key => $value) {
    if (count($result) > 24) {
      break;
    }
    array_push($result, [$key, $value]);
  }
  return $result;
}

/**
 * Add coloring to the bar chart, alternating every 5
 * @param $bar_array the current array of bar chart values
*/
function addColor($bar_array) {
  array_push($bar_array[0], "{ role: 'style' }");
  for ($i = 1; $i < count($bar_array); $i+=5) {
    array_push($bar_array[$i], "color: #FF5A5F");
  }
  for ($i = 2; $i < count($bar_array); $i+=5) {
    array_push($bar_array[$i], "color: #FFA500");
  }
  for ($i = 3; $i < count($bar_array); $i+=5) {
    array_push($bar_array[$i], "color: #008000");
  }
  for ($i = 4; $i < count($bar_array); $i+=5) {
    array_push($bar_array[$i], "color: #00b3ca");
  }
  for ($i = 5; $i < count($bar_array); $i+=5) {
    array_push($bar_array[$i], "color: #FFFF00");
  }
  for ($i = 0; $i < count($bar_array); $i++) {
    array_map("utf8_encode", $bar_array[$i]);
  }
  return $bar_array;
}

//create and store the "cleaned" words mapped to their frequency
set_time_limit(300);
$counts = countCleaned();
store_array("words.json", $counts);

//get the bar chart values and store them for later use
$data = createBar($counts, "Word", "Frequency");
$data = addColor($data);

store_array("reviews.json", $data);

?>

<html>
  <title> Bar Chart </title>
<body>

<!--Test Barchart -->
<div id="barchart_values" style="width: 900px; height: 300px;"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    var bar = <?php echo json_encode($data); ?>;
    bar[0] = ["Word", "Frequency", { role: "style" } ];
    function drawChart() {

      var data = google.visualization.arrayToDataTable(bar);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1, 2]);

      var options = {
        title: "Most Frequently Used Words in Reviews",
        width: 800,
        height: 800,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
</script>

</body>
</html>