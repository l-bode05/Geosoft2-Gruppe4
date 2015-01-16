'use strict';

    L.mapbox.accessToken = '<your access token here>';

    // Replace 'examples.map-i87786ca' with your map id.
    var mapboxTiles = L.tileLayer('https://{s}.tiles.mapbox.com/v3/examples.map-i87786ca/{z}/{x}/{y}.png', {
        attribution: '<a href="http://www.mapbox.com/about/maps/" target="_blank">Terms &amp; Feedback</a>'
    });

    var map = L.map('map')
        .addLayer(mapboxTiles)
        .setView([51.96217, 7.62561], 12);
          //different layer

    var aerial = L.tileLayer(
      'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; ' + mapLink 
      });
     var mapLink = '<a href="http://www.esri.com/">Esri</a>';
     var wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

    var mapQuestOpen_Aerial = L.tileLayer('http://oatile{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg', {
       attribution: 'Tiles Courtesy of <a href="http://www.mapquest.com/">MapQuest</a> &mdash; Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency', subdomains: '1234'
       });

    var osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
       attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'

   });


          //layer control b< marike

   L.control.layers({
       'Aerial': aerial,
       'mapboxTiles': mapboxTiles,
       'OpenStreetMap':osm,
       }, {}, {
      position: 'topleft'
    }).addTo(map);

     //locate user by luisa

     map.locate({setView: true , maxZoom: 13});


    
    var lng;
    var lat;
    var marker = L.marker();
    var popup = L.popup();
			
				//comment on map by marike
    function onMapClick(e) {
		  //  popup
		    //    .setLatLng(e.latlng)
		      //  .setContent("Comment for " + e.latlng.toString())
		        //.openOn(map);
          //  marker
           // 	.setLatLng(e.latlng)
          //  	.addTo(map);
                
          //     marker.bindPopup(markerHtml + e.latlng.toString());

            lng = e.latlng.lng;
            lat = e.latlng.lat;
            document.getElementById("Latitude").value = lat;
            document.getElementById("Longitude").value = lng;
            document.getElementById("f_Latitude").value = lat;
            document.getElementById("f_Longitude").value = lng;
            
            
           marker.setLatLng(e.latlng).addTo(map);
           marker.bindPopup('Position for your comment: ' + e.latlng.toString());
           UpdateCircle(lat, lng,document.getElementById("distance").value);
             
    }
    var circ;
    
    // Draw the are circle
    function UpdateCircle(lat, lng, distance) {
        distance=distance*100;
        
        if(circ!=null) map.removeLayer(circ);
        circ=null;
        if(distance>0) {
            circ=L.circle([lat,lng], distance); 
            circ.addTo(map);
        }
    }

    map.on('click', onMapClick);


  
   
   var markers = [];
    
   // Create a marker on the map with link     
   function CreateMarker(x, y, htmltxt, commentid, url_id2) {
        var marker = L.marker();
        var popup = L.popup();
        var url_id2= "http://gis.srh.noaa.gov/arcgis/services/NDFDTemps/MapServer/WMSServer";
        var markerHtml = htmltxt+" <a href='#' onclick='ShowComment("+commentid+")'> Show more</a>" 
        + " <br> <a href='#' onclick='ShowLayer()'>Show Layer "+url_id2+" </a>";


        var newLatLng = new L.LatLng(x, y);
        marker.setLatLng(newLatLng).addTo(map);
        marker.bindPopup(markerHtml);
        
        if(markers==null) markers = new Array();
        markers.push(marker);
    }     
    
    
   // Show a comment on the sidebar    
   function ShowComment(commentid) {
        ShowTab("panel_showcomment");
        // Insert Content
        InsertCommentDatas(commentid);
    } 
    
    // Show a comment on the sidebar    
   function ShowTab(panel_name) {
        // Show only hidden "Show Comment" tab
        var y = document.getElementsByClassName('content active'); 
        if(y.length>0) y[0].className="content";
        document.getElementById('panel1').className="content";
        document.getElementById('panel2').className="content";
        document.getElementById('panel3').className="content";
        
        document.getElementById(panel_name).className="content active";
        var y = document.getElementsByClassName('active');
        y[0].className="false";
        
    } 
    
    // Hide Event
    $(document).click(function(e) {
       if(document.getElementById('panel1').className!=="content" || 
           document.getElementById('panel2').className!=="content" ||
                   document.getElementById('panel3').className!=="content") {
                       document.getElementById('panel_showcomment').className="content";
                   };
    });
    
    // Read Comment datas from DB
    // JQUERY
    function InsertCommentDatas(commentid) {
        $('#showncomments').html("Loading..");
        // Submit Post Request
        $.post('ret_getcommentthread.php', "id="+commentid, function(data){
                $('#showncomments').html(data.split("°")[0]);
                var objs = jQuery.parseJSON(data.split("°")[1]);    
                ShowPosition(objs[0]["positionx"], objs[0]["positiony"]);
              
        });
        
        $('#answer_to').val("commentid");
        
    }
    
    // Read Comment datas from DB
    // JQUERY
    function InsertCommentTagDatas(tag) {
        
        $('#showncomments').html("Loading..");
        // Submit Post Request
        $.post('ret_getfirstcomments.php', "tag="+tag, function(data){
                $('#showncomments').html(data.split("°")[0]);
                ShowMarkersFromJson(data.split("°")[1]);
        });
        
        $('#answer_to').val("commentid");
        
    }
    
// Delete all current markers and load new ones    
function ClearMarkers() {
    if(markers!=null) {
        for(var i=0; i<markers.length; i++) {
            map.removeLayer(markers[i]); 
        }
    }
}

// JQUERY
function ShowMarkers_all() {
    ClearMarkers();
    // Submit Post Request
    $.post('ret_getmarkerdatas.php', "cat=0", function(data){
        var objs = jQuery.parseJSON(data);    
            
        for(var i=0;i<objs.length;i++){
            var obj = objs[i];
            CreateMarker(obj["positionx"], obj["positiony"], obj["content"], obj["id"]);
        }
    });
}

// JQUERY
function ShowMarkersFromJson(jsonstr) {
    ClearMarkers();
   
    var objs = jQuery.parseJSON(jsonstr);    

    for(var i=0;i<objs.length;i++){
        var obj = objs[i];
        CreateMarker(obj["positionx"], obj["positiony"], obj["content"], obj["id"]);
    }
    
}
 // Goto Position
function ShowPosition(positionx, positiony) {
    map.panTo(new L.LatLng(positionx, positiony));
}
        
        
   
// Show a comment on the sidebar    
function ShowCommentsToTag(tag) {
     ShowTab("panel_showcomment");
     // Insert Content
     InsertCommentTagDatas(tag);
 } 
 
 
 // Rate a Geodata
 // JQUERY
 function RateGeodata(geodataid) {
    
    var e = document.getElementById("ratinglist");
    var rating = e.options[e.selectedIndex].value;
    
    $('#ratingdiv').html("Thanks for rating");
    
     // Submit Post Request
    $.post('ret_rategeodata.php', "geodataid="+geodataid+"&rating="+rating, function(data){
        
    });
 }
 
 
 
 // Show edit formular  
function ShowEditForm(id) {
    ShowTab("panel_showcomment");
     
    // Submit Post Request
    $.post('ret_editform.php', "id="+id, function(data){
            $('#showncomments').html(data);
    });
     
 }
 
 
// FillEditForm
function FillAnswerForm(title, content, resources) {
     $('#ans_title').val(title);     
     $('#ans_comment').val(content);
     $('#ans_resources').val(resources);
     $('#userdiv').html("");
     
}


// Show the user profile
function ShowUserProfile(id) {
    ShowTab("panel_showcomment");
     
    // Submit Post Request
    $.post('ret_getuserprofile.php', "id="+id, function(data){
            $('#showncomments').html(data);
    });
}


// Show Geodata on Map
/*function AddGeodata() {

   var url_id = document.getElementById(URL);
   var url_id2= 'http://gis.srh.noaa.gov/arcgis/services/NDFDTemps/MapServer/WMSServer'
   if(url_id2.contains("WMS") || url_id2.contains ("wms"))
    var wmslayer = L.tileLayer.wms (url_id2,{
    format: 'img/png',
    transparent:true,
    layers:16 
  }).addTo(map)
 // var layer = wmslayer;
}*/

  function ShowLayer(){
  var url_id = document.getElementById(URL);
  var url_id2= 'http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs'
   var wmslayer = L.tileLayer.wms (url_id2,{
    format: 'img/png',
    transparent:true,
    layers:16 
  }).addTo(map)

    wmslayer.bringToFront();

}



/*function ShowLayer(){
  var url_id = document.getElementById(URL);
  var url_id2= 'http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs'*/

// Add each wms layer using L.tileLayer.wms
/*var layerone = L.tileLayer.wms('http://gis.srh.noaa.gov/arcgis/services/NDFDTemps/MapServer/WMSServer', {
    format: 'img/png',
    transparent: true,
    layers: 16
}).addTo(map);

var layertwo = L.tileLayer.wms('http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs', {
    format: 'img/png',
    transparent: true,
    layers: 'RAS_RIDGE_NEXRAD'
}).addTo(map);

// Layer switcher
document.getElementById('layerone').onclick = function () {
    var enable = this.className !== 'active';
    layerone.setOpacity(enable ? 1 : 0);
    this.className = enable ? 'active' : '';
    return false;
};

document.getElementById('layertwo').onclick = function () {
    var enable = this.className !== 'active';
    layertwo.setOpacity(enable ? 1 : 0);
    this.className = enable ? 'active' : '';
    return false;
};

*/

