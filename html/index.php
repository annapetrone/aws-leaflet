<html>

<!-- turn on php display errors -->
<!--< ?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>-->

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>aws-leaflet by ampetr</title>

    <!--<link rel="stylesheet" href="stylesheets/styles.css">-->
    <link rel="stylesheet" href="stylesheets/github-light.css">
    <meta name="viewport" content="width=device-width">
    
	
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <link rel="stylesheet" href="http://leaflet.github.io/Leaflet.draw/leaflet.draw.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
	
     <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
     <script src="http://leaflet.github.io/Leaflet.draw/leaflet.draw.js"></script>
    
	
  </head>
  <body>
    <!--<div class="center">-->
   
    
      <header>
        <h1>Capital Bikeshare Trips </h1>
		<p>
		<a href='index.php'>Reset map</a>
		</p>
		
		<?php
		
		// apply defaults to form variables 
			date_default_timezone_set('America/New_York');
		
			$date_min = '2010-09-15';
			$date_max = '2016-03-31';
			
			// set variables to their default values 
			$submitted = 'No filters';
			$output_type = 'bubble';
				
				
			$color_by = 'trip_origins';
			$start_date = '2016-01-01';
			$end_date = $date_max;
			$time_period = 'all';
			
			$monday = 1;
			$tuesday = 1;
			$wednesday = 1;
			$thursday = 1;
			$friday = 1;
			$saturday = 1;
			$sunday = 1;
			
			$registered = 1;
			$casual = 1;
			
		?>
		  
  
        <?php 
			$submitted = $_REQUEST['submitted'] ?: 'No filters';
			
			if($submitted== 'Apply Settings'){ 
				$output_type = $_REQUEST['output_type'] ?: 'bubble';
				
			// if user has applied queries, get the values, where now a null means false. 
			// the ?: does a coalesce in php5.3 and higher 
				$color_by = $_REQUEST['color_by'] ?: 'trip_origins';
				$start_date = $_REQUEST['start_date'] ?: $start_date;
				$end_date = $_REQUEST['end_date'] ?: $end_date;
				$time_period = $_REQUEST['time_period'] ?: 'all'; 
				
				$monday = $_REQUEST['monday'] ?: 0; // if null then false 
				$tuesday = $_REQUEST['tuesday'] ?: 0;
				$wednesday = $_REQUEST['wednesday'] ?: 0;
				$thursday = $_REQUEST['thursday'] ?: 0;
				$friday = $_REQUEST['friday'] ?: 0;
				$saturday = $_REQUEST['saturday'] ?: 0;
				$sunday = $_REQUEST['sunday'] ?: 0;
				
				$registered = $_REQUEST['registered'] ?: 0;
				$casual = $_REQUEST['casual'] ?: 0;
			};
					
		
	   ?>
		
			
		 <form method ="get"> <!-- use "get" form so the form variables show in the url. use "post" to hide them from the url -->
		 <table style="width:80%">
			
		  
		  <tr>
		 
			<td>
				Color by:<br>
				  <input type="radio" name="color_by" value="trip_origins" <?php if(strcmp($color_by,'trip_origins')==0){echo "checked";}?>> Trip origins<br>
				  <input type="radio" name="color_by" value="trip_destinations" <?php if(strcmp($color_by,'trip_destinations')==0){echo "checked";}?>> Trip destinations 
			</td> 
			
			<td>	
				Date range:
				  <input type="date" name="start_date" value=<?php echo $start_date?> min=<?php echo $date_min?> max=<?php echo $date_max?>> 
				  <input type="date" name="end_date"   value=<?php echo $end_date?> min=<?php echo $date_min?> max=<?php echo $date_max?>> 
			</td>
			
			<td>
				Time period:
				<select name="time_period">
				  <option value="all" <?php if(strcmp($time_period,'all')==0){echo 'selected="selected"';}?>>All</option>
				  <option value="earlymorning" <?php if(strcmp($time_period,'earlymorning')==0){echo 'selected="selected"';}?>>Early morning (Midnight-5AM)</option>
				  <option value="morning" <?php if(strcmp($time_period,'morning')==0){echo 'selected="selected"';}?>>Morning (6-10AM)</option>
				  <option value="afternoon" <?php if(strcmp($time_period,'afternoon')==0){echo 'selected="selected"';}?>>Afternoon (11AM-3PM)</option>
				  <option value="evening" <?php if(strcmp($time_period,'evening')==0){echo 'selected="selected"';}?>>Evening (4PM-8PM)</option>
				  <option value="night" <?php if(strcmp($time_period,'night')==0){echo 'selected="selected"';}?>>Night (9PM-Midnight)</option>
				</select>
			</td>	
			
			
			<td>
				Day(s) of week:
					<table>
					<td>
					  <input type="checkbox" name="monday" value=1 <?php if($monday==1){echo 'checked';}?>> Monday<br>
					  <input type="checkbox" name="tuesday" value=1 <?php if($tuesday==1){echo 'checked';}?>> Tuesday<br>
					  <input type="checkbox" name="wednesday" value=1 <?php if($wednesday==1){echo 'checked';}?>> Wednesday<br>
					  <input type="checkbox" name="thursday" value=1 <?php if($thursday==1){echo 'checked';}?>> Thursday<br>
					</td>
					<td>
					  <input type="checkbox" name="friday" value=1 <?php if($friday==1){echo 'checked';}?>> Friday<br>
					  <input type="checkbox" name="saturday" value=1 <?php if($saturday==1){echo 'checked';}?>> Saturday<br>
					  <input type="checkbox" name="sunday" value=1 <?php if($sunday==1){echo 'checked';}?>> Sunday<br>
					</td>
					</table>
			</td>
			
			 
			<td>
				Member type:<br>
				  <input type="checkbox" name="registered" value=1 <?php if($registered==1){echo 'checked';}?>> Cabi Members<br>
				  <input type="checkbox" name="casual" value=1 <?php if($casual==1){echo 'checked';}?>> Casual Users
			</td>
			 
			 
			<!--<td>
				Output type:<br>
				  <input type="radio" value="bubble" name="output_type" checked> Bubble plot <br>
				  <input type="radio" value="choro" name="output_type" > Choropleth
			</td>-->
			 
			
			<td>
			<!-- action is the current page -->
				<input type="submit" name='submitted' value="Apply Settings" action='' >
			</td>
		  
			
		  </tr>
		</table>
		</form>
      </header>
       
      
	   

  	   <?php
			pg_query("set time zone 'America/New_York';");// set the timezone 
		
			// build query based on form variables 
			
			$from_table = 'rides_v';
			
			if($color_by=='trip_origins'){
				$pfx = 'start';
			}else{
				$pfx = 'end';
			}
			$weekdays = array("'Monday'","'Tuesday'","'Wednesday'","'Thursday'","'Friday'","'Saturday'","'Sunday'");
			$include_weekday = array((int)$monday,(int)$tuesday,(int)$wednesday,(int)$thursday,(int)$friday,(int)$saturday,(int)$sunday);
			if(array_sum($include_weekday)==7){
				$where_weekday='1=1';
			}else{
				$where_weekday=$pfx."_weekday in ('dummy'";
				foreach(array(0,1,2,3,4,5,6) as &$i){
					if($include_weekday[$i]==1){
						$where_weekday .= ', ' . $weekdays[$i];
					}
				}
				$where_weekday .=  ')';
			}
			$member_types = array("'Registered'","'Casual'");
			$include_member = array((int)$registered,(int)$casual);
			if(array_sum($include_member)==2){
				$where_member='1=1';
			}else{
				$where_member="member_type in ('dummy'";
				foreach(array(0,1) as &$i){
					if($include_member[$i]==1){
						$where_member .= ', ' . $member_types[$i];
					}
				}
				$where_member .=  ')';
			}
			if($time_period=='all'){
				$where_time='1=1';
			}else{
				
				if(strcmp($time_period,'earlymorning')==0){
					$where_time = "between 0 and 5";
				}else if(strcmp($time_period,'morning')==0){
					$where_time = "between 6 and 10";
				}else if(strcmp($time_period,'afternoon')==0){
					$where_time = "between 11 and 3+12";
				}else if(strcmp($time_period,'evening')==0){
					$where_time = "between 4+12 and 8+12";
				}else if(strcmp($time_period,'night')==0){
					$where_time = "between 9 and 23";
				}				
				$where_time = $pfx."_hour ".$where_time;
			}
			
			$RAND = rand(1,10000); // in case multiple users accessing at the same time, make sure to use different table names
				
			$make_temp_table = "
				create table map_temp_$RAND 
				as 
				select ".$pfx."_terminal_id as terminal_id, count(*) as n_rides
				from ".$from_table."
				where ".$pfx."_time between '".$start_date." 00:00:00' and '".$end_date." 23:59:59'
				and ".$where_member."
				and ".$where_time."
				and ".$where_weekday."
				
				 group by 1;";
				
			echo "<!-- $make_temp_table -->";
			
			$get_data = "SELECT row_to_json(fc) as json_feature_list
				 FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
				 FROM (SELECT 'Feature' As type
					, ST_AsGeoJSON(the_geom)::json As geometry
					, row_to_json((SELECT l FROM (SELECT n_rides, lg.terminal_id, station_name) As l
					  )) As properties
				   FROM stations As lg
				   join map_temp_$RAND as m 
				   on lg.terminal_id = m.terminal_id::int
				   ) As f )  As fc;
				";
				
			$get_metros = "SELECT row_to_json(fc) as json_feature_list
				 FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
				 FROM (SELECT 'Feature' As type
					, ST_AsGeoJSON(the_geom)::json As geometry
					, row_to_json((SELECT l FROM (SELECT station_name, description, line) As l
					  )) As properties
				   FROM metro_entrances As lg
				   ) As f )  As fc;
				";
			 
			$calculate_quantiles = "
				select percentile_cont(ARRAY[0,.1,.2,.3,.4,.5,.6,.7,.8,.9,1]) WITHIN GROUP (ORDER BY n_rides) as n_rides_quantiles
					from map_temp_$RAND;
				";
			
			
			$dbpass = file_get_contents('../dbpass.txt');
			$host = file_get_contents('../host.txt');
			$dbconn = pg_connect("dbname='cabi' host=$host port=5432 user='postgres' password=$dbpass connect_timeout=5") ;
			
			// drop the temp table & recreate 
			pg_query("BEGIN");
				
			pg_query("drop table if exists map_temp_$RAND;") or die('<br><b>Drop failed: ' .  pg_result_error_field().'</b>');
			pg_query($make_temp_table)  or die('<br><b>Create failed: ' .  pg_result_error_field() .'</b>');
			pg_query("COMMIT");
			
			 
			
			
			$result = pg_query($get_data); # or die('Query failed: ' .  pg_result_error_field() );
			$dat = pg_fetch_result($result,  0, 'json_feature_list');
			
			$metros = pg_fetch_result(pg_query($get_metros), 0, 'json_feature_list');
			

			$quantiles = pg_query($calculate_quantiles);
			$quantiles = pg_fetch_result($quantiles, 0, 'n_rides_quantiles');
			
			$quantiles = str_replace('{','[',$quantiles);
			$quantiles = str_replace('}',']',$quantiles);
			
			$system_wide = pg_fetch_result(pg_query("select sum(n_rides) as total_rides from map_temp_$RAND"), 0, 'total_rides');
			
			pg_query("drop table if exists map_temp_$RAND;") or die('<br><b>Drop failed: ' .  pg_result_error_field().'</b>');
			
			// Closing connection
			pg_close($dbconn);
	?>
	
     
	  <p>
	   <h2> System wide: <?php echo number_format($system_wide) ?> rides </h2>
	   <div id="map"></div>
       </p>
	  
	
	
	   
        <script>
		
	 

		// Create Leaflet map object
		var map = L.map('map',{ center:  [38.912522, -77.031382], zoom: 14});  
		  // confusing! center must be specified as (lat,lon), while geojson coords are (lon,lat)
			
		// Add Tile Layer basemap
		var Esri_WorldGrayCanvas = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
			attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ',
			maxZoom: 20
		});
        // plenty more leaflet basemaps here https://leaflet-extras.github.io/leaflet-providers/preview/
		
     	Esri_WorldGrayCanvas.addTo(map); 
		var quantiles = <?php echo $quantiles; ?>;
			// quantiles will be turned into a javascript array 
		
		
		// function for coloring and sizing points by value 
		function getColor(d) { // check here for colors http://colorbrewer2.org/
			return (
			d > quantiles[10] ? '#800026v' : 
			d > quantiles[9] ? '#bd0026' :
			d > quantiles[8] ? '#e31a1c' :
			d > quantiles[7] ? '#fc4e2a' :
			d > quantiles[6] ? '#fd8d3c':
			d > quantiles[5] ? '#feb24c' :
			d > quantiles[4] ? '#fed976' :
			d > quantiles[3] ? '#ffeda0':
			d > quantiles[2] ? '#ffffcc' :
			  '#ffffe6' ); 
		};
		function getSize(d) {
			smallest= 3;
			x = 1.2;
			return (
			d > quantiles[10] ? smallest+9*x : 
			d > quantiles[9] ?  smallest+8*x:
			d > quantiles[8] ?  smallest+7*x :
			d > quantiles[7] ?  smallest+6*x:
			d > quantiles[6] ?  smallest+5*x:
			d > quantiles[5] ?  smallest+4*x:
			d > quantiles[4] ?  smallest+3*x:
			d > quantiles[3] ?  smallest+2*x:
			d > quantiles[2] ?  smallest+x:
			  smallest ); 
		} ;
		
		// function for styling bike station points 
		function style_point(feature)  {
			return {
				radius: getSize(feature.properties.n_rides),
				fillColor: getColor(feature.properties.n_rides),
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.8
			};
		};
		
		// function for styling metro points 
		function style_metro(feature)  {
			return {
				radius: 2.5,
				//fillColor: "#000",
				//color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.6
			};
		}; 
		
		// function for creating bike station popups
		function onEachFeature_point(feature, layer) {
			// bind whatever popUp info you want 
			if (feature.properties ) { //&& feature.properties.neighborhood_name
				layer.bindPopup(
					'<b>'+feature.properties.station_name+'</b> ('+feature.properties.terminal_id+')<br>'+
					'Rides taken: '+feature.properties.n_rides+'<br>'
				);
			}
			};

		// function for creating metro station popups
		function onEachFeature_metro(feature, layer) {
			// bind whatever popUp info you want 
			if (feature.properties ) { //&& feature.properties.neighborhood_name
				layer.bindPopup(
					'<b>'+feature.properties.station_name+'</b><br>'+
					feature.properties.description
				);
			}
			};
			
		
		 var dat = <?php echo $dat; ?>; 
			// the php will dump out the json, so that when it's evaluated, dat becomes a json object 
		
		 var metros = <?php echo $metros; ?>; 
		
		
		 // add metro entrances to map 
		 L.geoJson(metros, {
				pointToLayer: function (feature, latlng) {
					return L.circleMarker(latlng, style_metro(feature)); //
					},
				onEachFeature: onEachFeature_metro
			}).addTo(map);
			
			
		 // add bike stations to map 
		 L.geoJson(dat, { 
				pointToLayer: function (feature, latlng) {
					return L.circleMarker(latlng, style_point(feature)); //
					},
				onEachFeature: onEachFeature_point
			}).addTo(map);
			
		
		
      </script>
	  
	   <footer>
       <p>This project is available on <a href="https://github.com/ampetr/aws-leaflet" target='_blank'>Github</a></p>
        <p>Data source: <a href='https://www.capitalbikeshare.com/trip-history-data' target='_blank'>Capital bikeshare system data</a>, 
		<a href='http://opendata.dc.gov/datasets/556208361a1d42c68727401386edf707_111' target='_blank'>Metro station entrances</a>,
		<a href='https://www.capitalbikeshare.com/data/stations/bikeStations.xml' target='_blank'>Bikeshare station locations</a>
		</p>
		 
      </footer>
    

	<!-- </div> -->
     
   
    
  </body>
</html>