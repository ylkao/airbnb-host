<?php


//$txt = file_get_contents("reviews_clean.txt");
/*$counts = [];
$excluded = ["San", "Francisco", "The", "place", "room", "rooms", " "];
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
*/

function store_array($file, $arr) {
  file_put_contents($file, json_encode($arr));
}

//store_array("review_words.json", $counts);

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

$arr = getData("review_words.json");
$data = createBar($arr, "Word", "Frequency");
store_array("review_bar.json", $data);
?>

<html>
<body>

<!--Barchart -->
<div id="barchart_values" style="width: 900px; height: 300px;"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    var bar = <?php echo json_encode($data); ?>;
    function drawChart() {

      var data = google.visualization.arrayToDataTable(bar);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1]);

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