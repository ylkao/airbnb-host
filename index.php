<!DOCTYPE html>
<html>
<head>
  <title>Airbnb for Hosts</title>
</head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  body {
      font: 400 15px Lato, sans-serif;
      line-height: 1.8;
      color: #818181;
  }
  h2 {
      font-size: 24px;
      text-transform: uppercase;
      color: #303030;
      font-weight: 600;
      margin-bottom: 30px;
  }
  h4 {
      font-size: 19px;
      line-height: 1.375em;
      color: #303030;
      font-weight: 400;
      margin-bottom: 30px;
  }  
  .jumbotron {
      background-color: #FF5A5F;
      color: #fff;
      padding: 100px 25px;
      font-family: Montserrat, sans-serif;
  }
  .container-fluid {
      padding: 60px 50px;
  }
  .bg-grey {
      background-color: #f6f6f6;
  }
  .logo-small {
      color: #FF5A5F; /*f4511e*/
      font-size: 50px;
  }
  .logo {
      color: #FF5A5F; /*f4511e*/
      font-size: 200px;
  }

 /* .thumbnail {
      padding: 0 0 15px 0;
      border: none;
      border-radius: 0;
  }
  .thumbnail img {
      width: 100%;
      height: 100%;
      margin-bottom: 10px;
  }*/
  .carousel-control.right, .carousel-control.left {
      background-image: none;
      color: #FF5A5F; 
  }
  .carousel-indicators li {
      border-color: #FF5A5F; 
  }
  .carousel-indicators li.active {
      background-color: #FF5A5F; 
  }
  .item h4 {
      font-size: 19px;
      line-height: 1.375em;
      font-weight: 400;
      font-style: italic;
      margin: 70px 0;
  }
  .item span {
      font-style: normal;
  }
  .panel {
      border: 1px solid #FF5A5F; 
      border-radius:0 !important;
      transition: box-shadow 0.5s;
  }
  .panel:hover {
      box-shadow: 5px 0px 40px rgba(0,0,0, .2);
  }
  .panel-footer .btn:hover {
      border: 1px solid #FF5A5F; 
      background-color: #fff !important;
      color: #FF5A5F; 
  }
  .panel-heading {
      color: #fff !important;
      background-color: #FF5A5F !important; 
      padding: 25px;
      border-bottom: 1px solid transparent;
      border-top-left-radius: 0px;
      border-top-right-radius: 0px;
      border-bottom-left-radius: 0px;
      border-bottom-right-radius: 0px;
  }
  .panel-footer {
      background-color: white !important;
  }
  .panel-footer h3 {
      font-size: 32px;
  }
  .panel-footer h4 {
      color: #aaa;
      font-size: 14px;
  }
  .panel-footer .btn {
      margin: 15px 0;
      background-color: #FF5A5F; 
      color: #fff;
  }
  .navbar {
      margin-bottom: 0;
      background-color: #FF5A5F; 
      z-index: 9999; 
      border: 0;
      font-size: 12px !important;
      line-height: 1.42857143 !important;
      letter-spacing: 4px;
      border-radius: 0;
      font-family: Montserrat, sans-serif;
  }
  .navbar li a, .navbar .navbar-brand {
      color: #fff !important;
  }
  .navbar-nav li a:hover, .navbar-nav li.active a {
      color: #FF5A5F !important;
      background-color: #fff !important;
  }
  .navbar-default .navbar-toggle {
      border-color: transparent;
      color: #fff !important;
  }
  footer .glyphicon {
      font-size: 20px;
      margin-bottom: 20px;
      color: #FF5A5F; /*f4511e*/;
  }
  .slideanim {visibility:hidden;}
  .slide {
      animation-name: slide;
      -webkit-animation-name: slide;
      animation-duration: 1s;
      -webkit-animation-duration: 1s;
      visibility: visible;
  }
  @keyframes slide {
    0% {
      opacity: 0;
      transform: translateY(70%);
    } 
    100% {
      opacity: 1;
      transform: translateY(0%);
    }
  }
  @-webkit-keyframes slide {
    0% {
      opacity: 0;
      -webkit-transform: translateY(70%);
    } 
    100% {
      opacity: 1;
      -webkit-transform: translateY(0%);
    }
  }
  @media screen and (max-width: 768px) {
    .col-sm-4 {
      text-align: center;
      margin: 25px 0;
    }
    .btn-lg {
        width: 100%;
        margin-bottom: 35px;
    }
  }
  @media screen and (max-width: 480px) {
    .logo {
        font-size: 150px;
    }
  }

/* https://developers.google.com/maps/documentation/javascript/examples/places-searchbox */
/* Always set the map height explicitly to define the size of the div element that contains the map. */
  #map {
    height: 100%;
  }
  /* Optional: Makes the sample page fill the window. */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
  #description {
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
  }

  #infowindow-content .title {
    font-weight: bold;
  }

  #infowindow-content {
    display: none;
  }

  #map #infowindow-content {
    display: inline;
  }

  .pac-card {
    margin: 10px 10px 0 0;
    border-radius: 2px 0 0 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    outline: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    background-color: #fff;
    font-family: Roboto;
  }

  #pac-container {
    padding-bottom: 12px;
    margin-right: 12px;
  }

  .pac-controls {
    display: inline-block;
    padding: 5px 11px;
  }

  .pac-controls label {
    font-family: Roboto;
    font-size: 13px;
    font-weight: 300;
  }

  #pac-input {
    background-color: #fff;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    margin-left: 12px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 400px;
  }

  #pac-input:focus {
    border-color: #4d90fe;
  }

  #title {
    color: #fff;
    background-color: #4d90fe;
    font-size: 25px;
    font-weight: 500;
    padding: 6px 12px;
  }
  #target {
    width: 345px;
  }
/* end */

</style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

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

//global variables for graphing data
$scatter = getData("scatter.json");
$amenities = getData("amenities.json");
$reviews = getData("review_bar.json");

?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#myPage">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#data">DATA</a></li>
        <li><a href="#services">SERVICES</a></li>
        <li><a href="#contact">CONTACT</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron text-center">
  <h1>Airbnb for Hosts</h1> 
  <p></p> 
</div>

<!-- Container (About Section) -->
<div id="about" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h2>About Company Page</h2><br>
      <h4>Hosting Airbnb guests in your home can be an easy way to make extra money. That's especially true in San Francisco, where finding an affordable hotel or hostel can be difficult. Here at Airbnb for Hosts, we want to help new hosts figure out how to set the perfect price for their home and capitalize on new trends.</h4><br>
      <p>We offer a few tools to help you set up the perfect listing. View recent data of all listings in San Francisco with pricing, reviews, and more, including comparisons of the pricing of listings and number of people accommodated, the most offered amenities by hosts so far, and what people are looking for based on data collected from thousands of reviews. Also use our simple pricing tools to estimate the average weekly income you can make or calculate the ideal price per night to maximize bookings -- all you need is a geo-location!</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-home logo" style="margin-left: 30%;"></span>
    </div>
  </div>
</div>

<div class="container-fluid bg-grey">
  <div class="row">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-stats logo slideanim"></span>
    </div>
    <div class="col-sm-8">
      <h2>HOW WE DO IT</h2><br>
      <h4><strong>METHODS:</strong> To visualize the data, we relied on server-side PHP processing and the Google Charts API. To perform price estimation, we find the ten nearest listings to the given geo-location and average their prices. We also take into account a location's number of reviews when determining how to maximize bookings.</h4><br>
      <p><strong>NOTE:</strong> For a more detailed description of the implementation, please feel free to take a look at the source code and read more <a href="https://github.com/ylkao/airbnb-host.git">here.</a></p>
    </div>
  </div>
</div>

<!-- Container (Data Section) -->
<div id="data" class="container-fluid text-center bg-grey">
  <h2>Data</h2>
  <h4>What other hosts are doing</h4>
  <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
     <!--Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!--Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
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
              width: 800,
              height: 500
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
          }
        </script>
        <div id="piechart_3d" style="width: 800px; height: 530px; margin: auto;"></div>   
      </div>

      <div class="item">
        <div id="barchart_values" style="width: 800px; height: 500px; margin: auto;"></div>
        <script>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            var bar = <?php echo json_encode($reviews); ?>;
            function drawChart() {

              var data = google.visualization.arrayToDataTable(bar);

              var view = new google.visualization.DataView(data);
              view.setColumns([0, 1]);

              var options = {
                title: "Most Frequently Used Words in Reviews",
                width: 800,
                height: 500,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
              };
              var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
              chart.draw(view, options);
          }
        </script>
      </div>

  <div class="item">
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
          width: 800,
          height: 500,
          legend: 'none'
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 800px; height: 500px; margin: auto;"></div>
  </div>
</div>

     <!--Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>

     <div id="carouselButtons">
      <button id="playButton" type="button" class="btn btn-default btn-xs">
          <span class="glyphicon glyphicon-play"></span>
       </button>
      <button id="pauseButton" type="button" class="btn btn-default btn-xs">
          <span class="glyphicon glyphicon-pause"></span>
      </button>
    </div>

    <script>
      $(function () {
          $('#myCarousel').carousel({
              interval:4000,
              pause: "false"
          });
          $('#playButton').click(function () {
              $('#myCarousel').carousel('cycle');
          });
          $('#pauseButton').click(function () {
              $('#myCarousel').carousel('pause');
          });
      });
    </script>

  </div>
</div>


<div id="services" class="container-fluid text-center">
  <h2>Services</h2><br>
  <h4>What we offer</h4>
</div>

<!-- Container (Pricing Section) -->
<div id="pricing" class="container-fluid">
  <div class="text-center">
  </div>
  <div class="row slideanim">
    <div class="col-sm-4 col-xs-12">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <h1>Price Estimation</h1>
        </div>
        <div class="panel-body">
          <p>Enter a geo-location to estimate the average weekly income you can make with Airbnb!</p>
        </div>
        <div class="panel-footer">
        <input class="form-control" id="lat" name="lat" placeholder="Latitude" required><br>
        <input class="form-control" id="lon" name="lon" placeholder="Longitude" required>
          <button class="btn btn-lg" id="weekly" type="submit" onclick="calcWeekly()">Estimate Weekly Income</button>
          <p id="weekly_result"></p>
          <script>

          /** Calculate the estimated weekly income using AJAX */
          function calcWeekly() {
            document.getElementById("weekly").innerHTML = "Loading...";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                document.getElementById("weekly_result").innerHTML = this.responseText;
                document.getElementById("weekly").innerHTML = "Estimate Weekly Income";
              }
            };
            var lat = document.getElementById("lat").value;
            var lon = document.getElementById("lon").value;
            xhttp.open("GET", "weeklyIncome.php?lat="+lat+"&lon="+lon, true);
            xhttp.send();
          }

          </script>
        </div>
      </div>      
    </div> 

    <div class="col-sm-4 col-xs-12">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <h1>Popularity</h1>
        </div>
        <div class="panel-body">
          <p>Enter a valid zipcode in San Francisco to see its average rating! <br><strong>BONUS:</strong> The most popular neighborhood is the Financial District (zipcode: 94104) with an average rating of 97.33.</p>
        </div>
        <div class="panel-footer"><br>
          <input class="form-control" id="zipcode" name="zipcode" placeholder="Zipcode" required>
          <button class="btn btn-lg" id="popularity" type="submit" onclick="calcPopularity()">See Average Rating</button>

          <p id="popularity_result"></p>
          <script>

          /** Calculate the estimated weekly income using AJAX */
          function calcPopularity() {
            document.getElementById("popularity").innerHTML = "Loading...";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                document.getElementById("popularity_result").innerHTML = this.responseText;
                document.getElementById("popularity").innerHTML = "See Average Rating";
              }
            };
            var zip = document.getElementById("zipcode").value;
            xhttp.open("GET", "calcPopularity.php?zipcode="+zip, true);
            xhttp.send();
          }

          </script>
        </div>
      </div>      
    </div> 

    <div class="col-sm-4 col-xs-12">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <h1>Bookings Optimization</h1>
        </div>
        <div class="panel-body">
          <p>Enter a geo-location to calculate the ideal price per night to maximize your bookings!</p>
        </div>
        <div class="panel-footer">
          <input class="form-control" id="lat2" name="lat2" placeholder="Latitude" required><br>
        <input class="form-control" id="lon2" name="lon2" placeholder="Longitude" required>
          <button class="btn btn-lg" id="optimal" type="submit" onclick="calcOptimal()">Calculate Optimal Price</button>
          <p id="optimal_result"></p>
          <script> 

          /** Calculate the estimated weekly income using AJAX */
          function calcOptimal() {
            document.getElementById("optimal").innerHTML = "Loading...";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                document.getElementById("optimal_result").innerHTML = this.responseText;
                document.getElementById("optimal").innerHTML = "Calculate Optimal Price";
              }
            };
            var lat = document.getElementById("lat2").value;
            var lon = document.getElementById("lon2").value;
            xhttp.open("GET", "optimalPrice.php?lat="+lat+"&lon="+lon, true);
            xhttp.send();
          }
          
          </script>

        </div>
      </div>      
    </div>       
   
  </div>
</div>


<input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
<script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 37.7816, lng: -122.4194},
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtzZ5LFA8CakJU9qamVTPafXKxGn21U4A&libraries=places&callback=initAutocomplete"
         async defer></script>

<!-- Container (Contact Section) -->
<div id="contact" class="container-fluid bg-grey">
  <h2 class="text-center">CONTACT</h2>
  <div class="row">
    <div class="col-sm-5">
      <p>Thank you for visiting Airbnb for Hosts! Please fill out this form or use one of the below methods to get in touch with us. We would love to hear your feedback and what you think we can do better!</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> Berkeley, CA 94720</p>
      <p><span class="glyphicon glyphicon-phone"></span> +01 1234567890</p>
      <p><span class="glyphicon glyphicon-envelope"></span> customerservice@airbnbhost.com</p>
    </div>
    <div class="col-sm-7 slideanim">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5"></textarea><br>
      <div class="row">
        <div class="col-sm-12 form-group">
          <button class="btn btn-default pull-right" type="submit" onclick="clearForm()">Send</button>

          <script> 
          /* Clear contact form when pressing send */
            function clearForm() {
              document.getElementById("name").value = '';
              document.getElementById("email").value = '';
              document.getElementById("comments").value = '';
            }
          </script>
        </div>
      </div>
    </div>
  </div>
</div>
      
<footer class="container-fluid text-center">
  <a href="#myPage" title="To Top">
    <span class="glyphicon glyphicon-chevron-up"></span>
  </a>
  <!--Bootstrap Theme Made By <a href="https://www.w3schools.com" title="Visit w3schools">www.w3schools.com</a>-->
</footer>

<script>
$(document).ready(function(){
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
  
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
})
</script>

</body>
</html>
