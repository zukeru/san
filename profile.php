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
		#chats {
		    position: fixed;
		    right: 0;
		    top:0;
		    width: 20%;
		    height: 100%;
		    background-color: #c0c0c0;
		    padding:30px;
		    overflow-x: hidden; 
    		overflow-y: scroll;
		}
		#footer {
		    position: fixed;
		    bottom: 0;
		    width: 100%;
		    background-color: #000000;
		    padding:5px;
		}
	  </style>
	  <title>SAN</title>
  </head>

<body>
		<div id="page">
			<div class="header">
				<a href="#menu"></a>
				<font color="#ffffcc">Social Awareness Network</font>
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
					<li><center><p id='profile_image_place'></p><?php echo $_SESSION['login_username']; ?></center></li>
					<li><a href="#">Find Friends</a>
					<ul><li>
								    <form method="post" id="search_friends">
						          	<input type="text" id='searchstring' name="searchstring" class="form-control required" placeholder="search for friends" onkeypress="search();" />
						          	</form>

						          	 <p id ='list_friends'></p>
				    </li></ul>
					<li><a href="#">Friends</a><ul><p id ='my_friends'></p></ul></li>
					<li><a href="#">Friend Requests</a><ul><p id ='friend_request_list'></p></ul></li>
					<li><a href="#">Upload Profile Image</a><ul><li>
						<form id="uploadimage" method="POST" action="" enctype="multipart/form-data">
							<div id="selectImage">
								<center>
									<label>Select Your Image</label><br/>
									<input type="file" name="file" id="file" required />
									<input type='hidden' name='user_name' id='user_name'><br>
									<button  style="width:100%;" onclick="uploadImage();" class="btn btn-primary">Upload</button>
								</center>
							</div>
						</form>
					</li></ul></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>

			</nav>
		</div>
		<div id="chats">

		</div>

		<div id="footer">
			
			    <input type="text" style="width:94.5%; border-radius:5px; height:40px;" name="chatInput" id="chatInput" placeholder="enter your message here">
			    <button style="float:right;"  onclick="send_chat();" class="btn btn-success">Send</button>
			  
		</div>
<script>
      var lat_ret = 0;
      var lon_ret = 0;
      var map;
      var first = "true";

      function resize_map(){
      	 document.getElementById("footer").style.width =  screen.availWidth + "px";
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
	                   	 var returned_friends = JSON.parse(data); 
	                   	 var html_built = '<br>';
	                   	 var search_profile_pic = '';
	                   	 if (returned_friends){ 
		                   	 $.each( returned_friends, function( key, value ) {
			                   	 $.each(value, function( keys, values ) {		
			                   	 		if (keys =="1"){
					                   		try{
					                   			search_profile_pic = "images/"+values.filename;
					                   			
					                   		}
					                   		catch(err){
					                   			search_profile_pic = "nopic.jpg";
					                   		}	
					                   	}
					            });
		                   		html_built += '<li style="padding:5px;"><center><a href="#"><img width="60" height="60" style="padding:5px;" src="'+search_profile_pic+'"><button class="btn btn-primary" style="width:70%;" id="'+value.username+'" onClick="add_friend(this.id)"> Send '+value.username+' A Friend Request</button><center></li>';
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
			                   	var friend_profile_pic = '';
			                   	if (current_friends){   
				                   	$.each( current_friends, function( key, value ) {
				                   		$.each(value, function( keys, values ) {
				                   			if (keys =="3"){
				                   				try{
				                   					friend_profile_pic = "images/"+values.filename;
				                   				}
				                   				catch(err){
				                   					friend_profile_pic = "nopic.jpg";
				                   				}	
				                   			}
				                   		});
			 							 html_built += '<li style="margin-left:5px; padding:5px;" "id="friend-'+value.friend_id+'"><img style="padding:3px;" src ="'+friend_profile_pic+'" width="60" height="60">'+ value.friend_id + '</li>';
									});
			                   }
			                   html_built +='';
			                   document.getElementById("my_friends").innerHTML = html_built;    
	                   }
	               });
	        return false;
      }

      var html_built = '';
      var old_chats = '';

      function get_chats(){
      	var sort_by = function(field, reverse, primer){

		   var key = primer ? 
		       function(x) {return primer(x[field])} : 
		       function(x) {return x[field]};

		   reverse = !reverse ? 1 : -1;

		   return function (a, b) {
		       return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
			} 
		}
	      	var url = "get_chats.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
			                   	var chats = JSON.parse(data); 
			                   	if (old_chats != chats){
				                   	if (chats){     
				                   		chats.sort(sort_by('timestamp', false, function(a){return a.toUpperCase()}  ));
				                   		document.getElementById("chats").innerHTML = '';
					                   	$.each( chats, function( key, value ) {
					                   	html_built+="<div id='chat' style='margin-bottom:10px;width:100%;background-color:#FFF;color:#202020;padding:10px;border-radius:15px;border-width:15px;'>"+value.username+ " says: " + value.message + " @: " + value.timestamp + "</div>";
										});
									 document.getElementById("chats").innerHTML = html_built;  
				                   	 old_chats = chats;
				                  }
				                   	 html_built = '';
			                   }
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
			 							 html_built += '<li id="request-'+value.user_id+'">'+
			 							 ' <a href="#"><button class="btn btn-primary" style="width:100%;" id="'+value.user_id+'" onClick="accept_request(this.id)">Accept '+value.user_id+' Friend Request</button></a></li>';
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

      function send_chat(){
	      	var url = "send_chat.php";      
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"chat" : document.getElementById("chatInput").value, "userid":document.getElementById("userid").value }, // serializes the form's elements.
	                 success: function(data)
	                   {   
						console.log(data); 
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
      var profile_pic = '';
      function get_profilepic(){
	      	var url = "get_profilepic.php";       
	        if($('#coords_form').valid()){
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: $("#coords_form").serialize(), // serializes the form's elements.
	                 success: function(data)
	                   {          
	                   	 var returned_pic = JSON.parse(data);  
	                   	 if (returned_pic){ 
	                   	 	document.getElementById("profile_image_place").innerHTML = "<img src='images/"+returned_pic.filename+"' style='border-color:#202020; margin-top:3px; border-width: 3px;border-style: solid;' width='150' height='150'>";
	                   		profile_pic = "images/"+returned_pic.filename;
	                   	}else{
	                   		document.getElementById("profile_image_place").innerHTML = "<img src='nopic.jpg' style='border-color:#202020; margin-top:3px; border-width: 3px;border-style: solid;' width='150' height='150'>";
	                   		profile_pic = "nopic.jpg";
	                   	}
	                   }
	               });
	             }
	        return false;
      }
      get_profilepic();

      var friend_profile_pic = '';
      function get_friend_profilepic(user){
	      	var url = "get_profilepic.php";   
	      	friend_profile_pic = ''; 
	      	try{   
	          $.ajax({
	            type: "POST",
	              url: url,
	               data: {"userid":user}, // serializes the form's elements.
	                 success: function(data)
	                   {          
	                   	 var returned_pic = JSON.parse(data);  

	                   	 if (returned_pic){ 
	                   		friend_profile_pic = "images/"+returned_pic.filename;
	                   	}else{
	                   		friend_profile_pic = "nopic.jpg";
	                   	}
	                   }
	               });
	        return false;
	    }
	    catch(err){

	    }
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

				                   			addFriendMarkerGroup(value.geo_lat,value.geo_lon,map,value.user_id,friend_profile_pic);  
									});
			                   }  
			           
	                   }
	               });
	        return false;
      }

      function saveCoords(lat_ret, lon_ret){
      		document.getElementById("lat_input").value = lat_ret;
      		document.getElementById("lon_input").value = lon_ret;
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

      	var profilePicIcon = L.icon({
		    iconUrl: profile_pic,
		    iconSize:     [50, 50], // size of the icon
		    iconAnchor:   [25, 25], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
		});


        map.removeLayer(markers);
        markers = new L.FeatureGroup();
        var marker = L.marker([lat_ret, lon_ret], {icon: profilePicIcon } ).addTo(map).bindPopup("User:" + user, {autoClose: false , autoPan: false }).openPopup();
        markers.addLayer(marker);
        map.addLayer(markers);
      }

	  var friend_markers = new L.FeatureGroup();

		function removeFriendMarkers(){
        	map.removeLayer(friend_markers);
        	friend_markers = new L.FeatureGroup();
		}

      function addFriendMarkerGroup(lat_ret,lon_ret,map,user,friend_profile_pic){
      	get_friend_profilepic(user);
	    if ( friend_profile_pic ) { 
	    	var friend_profile_pic = friend_profile_pic;
	    }else{
	    	var friend_profile_pic = 'nopic.jpg';
	    }

      	var friendprofilePicIcon = L.icon({
		    iconUrl: friend_profile_pic ,
		    iconSize:     [50, 50], // size of the icon
		    iconAnchor:   [25, 25], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
		});
      	if (friend_profile_pic){
	        var friend_marker = L.marker([lat_ret, lon_ret], {icon: friendprofilePicIcon } ).addTo(map).bindPopup("User:" + user,{autoClose: false,autoPan: false}).openPopup();
	        friend_markers.addLayer(friend_marker);
	        map.addLayer(friend_markers);
        }else{
        	var friend_marker = L.marker([lat_ret, lon_ret]).addTo(map).bindPopup("User:" + user,{autoClose: false,autoPan: false}).openPopup();
	        friend_markers.addLayer(friend_marker);
	        map.addLayer(friend_markers);

        }
      }

      function removeAllMarkers(){
          map.removeLayer(markers);
      }

      function uploadImage(){
      	var user_name = document.getElementById("userid").value;
      	document.getElementById("user_name").value = user_name;
      	var form = document.getElementById('uploadimage');
		var formData = new FormData(form);
		$.ajax({
			url: "upload_image.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				
			}
		});
		get_profilepic();
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
