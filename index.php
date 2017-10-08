<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 90%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      ul {
		    list-style-type: none;
		    margin: 0;
		    padding: 0;
		    overflow: hidden;
		    background-color: #333;
			}

			li {
			    float: left;
			}

			li a {
			    display: block;
			    color: white;
			    text-align: center;
			    padding: 14px 16px;
			    text-decoration: none;
			}

			li a:hover {
			    background-color: #111;
			}

			#snackbar {
			    visibility: hidden;
			    min-width: 250px;
			    margin-left: -125px;
			    background-color: #333;
			    color: #fff;
			    text-align: center;
			    border-radius: 2px;
			    padding: 16px;
			    position: fixed;
			    z-index: 1;
			    left: 50%;
			    bottom: 30px;
			    font-size: 17px;
			}

			#snackbar.show {
			    visibility: visible;
			    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
			    animation: fadein 0.5s, fadeout 0.5s 2.5s;
			}

			@-webkit-keyframes fadein {
			    from {bottom: 0; opacity: 0;} 
			    to {bottom: 30px; opacity: 1;}
			}

			@keyframes fadein {
			    from {bottom: 0; opacity: 0;}
			    to {bottom: 30px; opacity: 1;}
			}

			@-webkit-keyframes fadeout {
			    from {bottom: 30px; opacity: 1;} 
			    to {bottom: 0; opacity: 0;}
			}

			@keyframes fadeout {
			    from {bottom: 30px; opacity: 1;}
			    to {bottom: 0; opacity: 0;}
			}
    </style>
    <script type="text/javascript">
    	function snackbarshow(val){
    	var x = document.getElementById("snackbar")
    	x.innerHTML = "The current credit value is "+val;
    	x.className = "show";
    	setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }

     <?php
    		function conv(){
    			$conn = new mysqli("localhost", "root", "ass", "solar");

    			// Check connection
    			if ($conn->connect_error) {
    			    die("Connection failed: " . $conn->connect_error);
    			} 
    			//echo "Connected successfully";

    			 $sql    = "SELECT * FROM storage_data";
    			 $result = mysqli_query($conn,$sql);

    			 if (!$result) {
    			    echo "DB Error, could not query the database\n";
    			    echo 'MySQL Error: ' . mysqli_error();
    			    exit;
    			}
    			if(mysqli_num_rows($result) > 0)
    			while ($row = mysqli_fetch_array($result)) {
    					$out[$row['id']] = $row['storage_power'];
    			}
    			$out = json_encode($out);
    			mysqli_free_result($result);
    			echo "var js_array = ". $out . ";\n";
    		}
    		?>

    </script>
  </head>

  <body>

  	<div>
  		<ul>
  			<li><a class="active links" id="link1" href="#map">EnergyStations</a></li>
  			<li><a class = "links" id = "link2" href="#nothing">Show Credits</a></li>
  			<li><a class="links" id="link3" href="#nothing1">Edit Credits</a></li>
			</ul>
  	</div>
    <div id="map" class="divs"></div>
    <div id="nothing" class="divs">
    	<form method="post" action="test.php">
    		<fieldset>
    		    <legend>Personal information:</legend>
    		    Unique ID:<br>
    		    <input type="number" name="uid"><br>
    		    Name:<br>
    		    <input type="text" name="name"><br><br>
    		    <input type="submit" value="Submit" name="credit_disp">
    		  </fieldset>
    	</form>
    	<div id="snackbar"></div>
    	<?php
    		function display(){
    			$conn = new mysqli("localhost", "root", "ass", "solar");

    			// Check connection
    			if ($conn->connect_error) {
    			    die("Connection failed: " . $conn->connect_error);
    			} 
    			//echo "Connected successfully";

    			 $sql    = "SELECT credits FROM user_data WHERE unique_id = " .$_POST['uid']. " and name = '" .$_POST['name']."'";
    			 $result = mysqli_query($conn,$sql);

    			 if (!$result) {
    			    echo "DB Error, could not query the database\n";
    			    echo 'MySQL Error: ' . mysqli_error();
    			    exit;
    			}
    			if(mysqli_num_rows($result) > 0)
    			while ($row = mysqli_fetch_array($result)) {
    			    echo '<script type="text/javascript">','snackbarshow('.$row['credits'].')','</script>';
    			}

    			mysqli_free_result($result);
    		}
    		if(isset($_POST['credit_disp'])){
    			display();
    		}
    	?>
    </div>
    
    <div id="nothing1" class="divs"> 
    	<form method="post" action="test.php">
    		<fieldset>
    		    <legend>Update Credits:</legend>
    		    Unique ID of storage:<br>
    		    <input type="number" name="sid"><br>
    		    User ID:<br>
    		    <input type="number" name="uid"><br><br>
    		    Energy given in KWH:<br>
    		    <input type="number" name="energy"><br><br>
    		    <input type="submit" value="Submit" name="credit_update">
    		  </fieldset>
    	</form>
    	<?php
    		function display1(){
    			$conn = new mysqli("localhost", "root", "ass", "solar");

    			// Check connection
    			if ($conn->connect_error) {
    			    die("Connection failed: " . $conn->connect_error);
    			} 
    			//echo "Connected successfully";

    			 $sql    = "UPDATE user_data SET credits = credits + " .$_POST['energy']. " WHERE unique_id = " .$_POST['uid'];
    			 $sql1    = "UPDATE storage_data SET storage_power = storage_power + " .$_POST['energy']. " WHERE unique_id = " .$_POST['sid'];
    			 $result = mysqli_query($conn,$sql);
    			 $result1 = mysqli_query($conn,$sql1);
    			 if (!$result) {
    			    echo "DB Error, could not query the database\n";
    			    echo 'MySQL Error: ' . mysqli_error();
    			    exit;
    			}
    			if (!$result1) {
    			    echo "DB Error, could not query the database\n";
    			    echo 'MySQL Error: ' . mysqli_error();
    			    exit;
    			}
    			mysqli_free_result($result);
    			mysqli_free_result($result1);
    		}
    		if(isset($_POST['credit_update'])){
    			display1();
    		}
    	?>
    </div>
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
    	$('#nothing').hide();
    	$('#nothing1').hide();
    });
    $(document).ready(function () {
    	$('a.links').click(function (e){
		   e.preventDefault();
		   var div_id = $('a.links').index($(this))
		   $('.divs').hide().eq(div_id).show();
				});
    });

      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

      

        function initMap() {
        var x,y;
        navigator.geolocation.getCurrentPosition(function(location) {
  				<?php json_encode(conv())?>;
  				x = location.coords.latitude;
  				y = location.coords.longitude;
  				var map = new google.maps.Map(document.getElementById('map'), {
          			center: new google.maps.LatLng(x,y),
          			zoom: 10
        		});
        		var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('test.xml', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              console.log(Object.prototype.toString.call(id));
              id = parseInt(id);
              console.log(Object.prototype.toString.call(id));
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = js_array[id];
              console.log(icon);
              console.log(id);
              console.log(js_array[id]);
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
			});
      }

			function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsTvNLno9Ny93s-xaKuDU384WTXeQDXU8&callback=initMap">
    </script>
  </body>
</html>