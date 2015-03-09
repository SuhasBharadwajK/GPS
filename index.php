<!DOCTYPE PHP>

<!-- The 'html' tag. A web page starts and ends inside this tag. -->
<html>
    <!-- The 'head' tag. This tag contains things that describe the web page. It contains the information about the page and the external links to it. -->
	<head>
        <!-- The 'title' tag contains the title of the web page. -->
        <title>GPS Tacker</title>
        <!-- Google Maps API JavaScript Library -->
        <script src="http://maps.googleapis.com/maps/api/js"></script>
        <!-- The icon on the tab. -->
        <link rel="icon" href="map.png"/>
	</head>

    <!-- The 'script' tag. This tag is used for writing scripts in PHP and JavaScript internally. -->
	<script>
        //PHP script for reading the file and getting the values of latitude and longitude.
		<?php
			$file = file_get_contents("coords.txt"); //Open the file 'coords.txt'.
			$line = explode(" ", $file)[4]; //Read the first line of the file and using the 'explode' method,

            /*
             * Notes about 'explode' function: It takes a delimiter and a string and divides that string
             * into many pieces using that delimiter and returns an array of elements.
             * e.g.: a = explode(" ", "Hi! How are you?"); will return an array ["Hi!", "How", "are", "you?"] into 'a'.
             * Therefore, a[0] = "Hi!", a[1] = "How", a[2] = "are", a[3] = "you?"
             * This method is similar to 'split' method in Python.
             */

            $latdeg = (float) explode(" ", $file)[2]; //Get the latitude degrees from the file and convert it to a float.
            $latmin = (float) explode(" ", $file)[3]; //Get the latitude minutes from the file and covert it to a float.
            $latsec = (float) explode("N",explode(" ", $file)[4])[0]; //Get the latitude seconds of the file and convert it to float.
            //This instruction has two explode functions because the seconds given in the file are suffixed with an 'N' or an 'E'.
            //To prevent the parsing of the seconds part of the latitude (e.g.: 50.94N) as a string, this is written.

            $longdeg = (float) explode(" ", $file)[7]; //Get the longitude degrees from the file and convert it to a float.
            $longmin = (float) explode(" ", $file)[8]; //Get the longitude minutes from the file and covert it to a float.
            $longsec = (float) explode("E",explode(" ", $file)[9])[0];//Get the longitude seconds of the file and convert it to float.
            //This instruction has two explode functions because the seconds given in the file are suffixed with an 'N' or an 'E'.
            //To prevent the parsing of the seconds part of the latitude (e.g.: 50.94E) as a string, this is written.

            $latitude = $latdeg + $latmin/60 + $longsec/3600; //Calculate the latitude in decimal form, since the Google Maps API takes the coordinates in decimal form.
            //(60 minutes = 1 degree and 60 seconds = 1 minute)

            $longitude = $longdeg + $longmin/60 + $longsec/3600; //Calculate the longitude in decimal form, since the Google Maps API takes the coordinates in decimal form.
            //(60 minutes = 1 degree and 60 seconds = 1 minute)
		?>;

        //JavaScript code for plotting the latitude and longitude coordinates obtained form the file onto the map.
        function plotmap() {

            var lat = <?php echo json_encode($latitude); ?>; //Variable for storing the latitude value read from the file.
            var long = <?php echo json_encode($longitude); ?>; //Variable for storing the longitude value read from the file.

            var mapCenter = new google.maps.LatLng(lat, long); //Create a variable to represent the center of the map, from the given lat and long.

            var properties = {center: mapCenter, zoom: 15, mapTypeId: google.maps.MapTypeId.HYBRID}; //Create a variable for storing the properties of the map.
            //Attributes: center: The point around which the map is supposed to be centered. It is stored in the variable called 'mapCenter', declared above.
            //            zoom: The factor by which the map is to be zoomed into. Higher the number, more the zoom and vice versa. This is the number of scrolls-ups to be done to be zoomed to the center.
            //            mapTypeId: The type of the map to be shown. All the possible types are listed below with descriptions.
            //MapTypeID:  ROADMAP: For normal 2D map with city and road names.
            //            SATELLITE: For photographic view.
            //            HYBRID: For both names and photographic view. <-This is the current map type.
            //            TERRAIN: For showing mountains, rivers, etc., in the map.

            var map = new google.maps.Map(document.getElementById("plot"), properties); //Create a variable for the actual map with the properties created above.

            var marker = new google.maps.Marker({position: mapCenter}); //Create a variable for the marker to point to the center of the map.
            marker.setMap(map); //Place the marker on the map.
        }

    </script>

    <!-- The 'style' tag. This tag is used for writing CSS rules internally. -->
    <style>
        /*Created custom font faces of OpenType Format using the .otf files in the directory as source.*/
        @font-face {
            font-family: Lovelo-light;
            src: url('Lovelo-light.otf');
        }
        @font-face {
            font-family: Lovelo-bold;
            src: url('Lovelo-bold.otf');
        }

        /*CSS rules for the text 'GPS Tracker'*/
        #header {
            font-size: 72;
            font-family: Lovelo-light;
            position: absolute;
            margin-left: 35%;
            margin-top: 2.5%;
        }
    </style>
    <!-- The 'body' tag contains the body of the web page, or all the elements that are shown on in the browser window when the page loads.
    When the body of the page is loaded, the 'onload' event handler is invoked, which calls the 'plotmap()' function, which actually creates a map and plots the coordinates on it. -->
    <body onload="plotmap()">
        <!-- The 'div' tag signifies a division of the page. This tag is the area where the map is created. -->
        <div id="plot" style="width: 80%; height: 80%; margin: auto auto;"></div>
        <!-- This div tag is the place where the text 'GPS Tracker' appears on the page. -->
        <div id="header">GPS Tracker</div>
    </body>
</html>