<!DOCTYPE html>
<?php
include("session.php");
?>

<html>

  <head>
  		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

	  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
	  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	  
	  <link type="text/css" rel="stylesheet" href="css/demo.css" />
	  <link type="text/css" rel="stylesheet" href="/dist/core/css/jquery.mmenu.all.css" />

	  <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
	  <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	  <script type="text/javascript" src="/dist/core/js/jquery.mmenu.min.all.js"></script>
	  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	  <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
	  <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>	  
	  <style>
	  	body, html { 
    		overflow-x: hidden; 
    		overflow-y: hidden;
    		background-color: #000000;
		}
	  </style>
  </head>

<body>
		<div id="page">
			<div class="header">
				<a href="#menu"></a>
				San
			</div>
			<div class="content">
			      <form method="post" id="coords_form">
			      	<input type="hidden" id="lat_input" name="geo_lat"  class="form-control required">
			      	<input type="hidden" id="lon_input" name="geo_lon"  class="form-control required">
			      	<input type="hidden" id="userid" name="userid"  value=<?php echo $_SESSION['login_username']; ?> class="form-control required">
			      	<!--<div class="error" id="coords_error"></div> -->
			      </form>

			    <div id="map" style="width: 600px; height: 400px"></div>
      			<iframe src="background.html" style="display:none;"></iframe>
			</div>

			<nav id="menu">
				<ul>
					<li><a href="#">Hello <?php echo $_SESSION['login_username']; ?></a></li>
					<li><a href="#">Home</a></li>
					<li><a href="#">Find Friends</a>
					<ul><li>
								    <form method="post" id="search_friends">
						          	<input type="text" id='searchstring' name="searchstring" class="form-control required" placeholder="search for friends" onkeypress="search();" />
						          	</form>

						          	 <p id ='list_friends'></p>
				    </li></ul>
					<li><a href="#">Friends</a><ul><p id ='my_friends'></p></ul></li>
					<li><a href="#">Friend Requests</a><ul><p id ='friend_request_list'></p></ul></li>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
				
			</nav>
		</div>
<script>
      var lat_ret = 0;
      var lon_ret = 0;
      var map;
      var first = "true";

      function resize_map(){
      	 document.getElementById("map").style.width =  screen.availWidth + "px";
      	 document.getElementById("map").style.height =  screen.availHeight - 60 + "px";
      }
      resize_map();

      function search(){
	      	var url = "search_friends.php";       
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: $("#search_friends").serialize(), // serializes the form's elements.
	                 success: function(data)
	                   {   
	                   	console.log(data);       
	                   	 var returned_friends = JSON.parse(data); 
	                   	 var html_built = '<br>';
	                   	 console.log(returned_friends);
	                   	 console.log($("#search_friends").serialize());
	                   	 if (returned_friends){ 
		                   	 $.each( returned_friends, function( key, value ) {
		                   	 	if (key =="username"){
		                   	 		html_built += '<li><a href="#"><button class="btn btn-primary" style="width:100%;" id="'+value+'" onClick="add_friend(this.id)"> Send '+value+' A Friend Request</button></li>';
		                   	    }
		                   	 });
	                   	}
	                   	html_built += ""
	                   	 document.getElementById("list_friends").innerHTML = html_built;  
	                   	     
	                   }
	               });
	        return false;
      }

      function get_friends(){
	      	var url = "get_friends.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
			                   	var current_friends = JSON.parse(data); 
			                   	var html_built = '';
			                   	if (current_friends){   
				                   	$.each( current_friends, function( key, value ) {
				                   		if (key =="friend_id"){
			 							 html_built += '<li id="friend-'+value+'"><button class="btn btn-primary" style="width:100%;">'+ value + '</button></li>';
			 							}
									});
			                   }
			                   html_built +='';
			                   document.getElementById("my_friends").innerHTML = html_built;    
	                   }
	               });
	        return false;
      }

      function friend_requests(){
	      	var url = "friend_requests.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
			                   	var returned_friend_requests = JSON.parse(data); 
			                   	var html_built = '';
			                   	if (returned_friend_requests){     
				                   	$.each( returned_friend_requests, function( key, value ) {
				                   		if (key =="user_id"){
			 							 html_built += '<li id="request-'+value+'">'+
			 							 ' <a href="#"><button class="btn btn-primary" style="width:100%;" id="'+value+'" onClick="accept_request(this.id)">Accept '+value+' Friend Request</button></a></li>';
			 							}
									});
			                   }
			                   	 html_built += '';
			                   	 document.getElementById("friend_request_list").innerHTML = html_built;    
	                   }
	               });
	        return false;
      }

      function accept_request(id){
	      	var url = "accept_request.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"friendname" : id, "userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
	                   	var request_element = 'request-'+id;
	                   	document.getElementById(request_element).remove();
	                   	document.getElementById("list_friends").innerHTML = "";
	                   	document.getElementById("searchstring").innerHTML = ""; 
	                   }
	               });
	        return false;
      }

      function add_friend(id){
	      	var url = "add_friends.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"friendname" : id, "userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
						//console.log(data); 
	                   }
	               });
	        return false;
      }

      function initiate_geolocation() {
          navigator.geolocation.getCurrentPosition(handle_geolocation_query);
      }
   
      function handle_geolocation_query(position){
          //var html_string = 'Lat: ' + position.coords.latitude + ' ' + 'Lon: ' + position.coords.longitude;
          //document.getElementById("geo").innerHTML = html_string;
          
          lat_ret = position.coords.latitude;
          lon_ret = position.coords.longitude;

          if (first === "true"){
            addMap(lat_ret,lon_ret);
            addMarkerGroup(lat_ret,lon_ret,map);
            first = "false";
          }
          //addMarkerGroup(lat_ret,lon_ret,map);
          saveCoords(lat_ret, lon_ret);
          getCoords();
      }
    
      function addMap(){
        map = L.map('map', { zoomControl:false }).setView([lat_ret, lon_ret], 13);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
          maxZoom: 18,
          attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
          id: 'mapbox.streets'
        }).addTo(map);
      }

      
      function getCoords(){
	      	var url = "get_coords.php";       
	        if($('#coords_form').valid()){
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: $("#coords_form").serialize(), // serializes the form's elements.
	                 success: function(data)
	                   {          
	                   	 var returned_coords = JSON.parse(data);   
	                   	 addMarkerGroup(returned_coords.geo_lat,returned_coords.geo_lon,map,returned_coords.user_id);     
	                   }
	               });
	             }
	        return false;
      }

      function get_friend_coords(){
	      	var url = "friend_coords.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"userid":document.getElementById("userid").value }, // serializes the form's elements.
	               success: function(data)
	                   {   
			                   	var friend_coords = JSON.parse(data); 
			                   	if (friend_coords){ 
			                   		removeFriendMarkers();  
				                   	$.each( friend_coords, function( key, value ) {
				                   			addFriendMarkerGroup(value.geo_lat,value.geo_lon,map,value.user_id);  
									});
			                   }  
			           
	                   }
	               });
	        return false;
      }

      function saveCoords(lat_ret, lon_ret){
      		document.getElementById("lat_input").value = lat_ret;
      		document.getElementById("lon_input").value = lon_ret;
      		//console.log($("#coords_form").serialize());
	      	var url = "save_coords.php";       
	        if($('#coords_form').valid()){
	          $('#coords_error').html('Please wait...');  
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: $("#coords_form").serialize(), // serializes the form's elements.
	                 success: function(data)
	                   {          
	                   	 //console.log(data);          
	                     if(data==1) {               
	                       //$('#coords_error').html('Saved...');
	                       //window.location.href = "profile.php";
	                      } 
	                     else {
	                           //$('#coords_error').html('there was an issue saving the coords..');
	                           //$('#coords_error').addClass("error");
	                         }
	                   }
	               });
	             }
	        return false;
      }
	  var markers = new L.FeatureGroup();
      function addMarkerGroup(lat_ret,lon_ret,map,user){
        map.removeLayer(markers);
        markers = new L.FeatureGroup();
        var marker = L.marker([lat_ret, lon_ret]).addTo(map).bindPopup("User:" + user).openPopup();
        markers.addLayer(marker);
        map.addLayer(markers);
      }

	  var friend_markers = new L.FeatureGroup();

		function removeFriendMarkers(){
        	map.removeLayer(friend_markers);
        	friend_markers = new L.FeatureGroup();
		}

      function addFriendMarkerGroup(lat_ret,lon_ret,map,user){
        var friend_marker = L.marker([lat_ret, lon_ret]).addTo(map).bindPopup("User:" + user).openPopup();
        friend_markers.addLayer(friend_marker);
        map.addLayer(friend_markers);
      }

      function removeAllMarkers(){
          map.removeLayer(markers);
      }
		$(function() {
				$('nav#menu').mmenu({
					extensions	: [ 'effect-slide-menu', 'pageshadow' ],
					counters	: true,
					navbar 		: {
						title		: 'SAN'
					},
					navbars		: [
						 {
							position	: 'top',
							content		: [
								'prev',
								'title',
								'close'
							]
						}
					]
				});
			});
    </script>
  </body>
</html>