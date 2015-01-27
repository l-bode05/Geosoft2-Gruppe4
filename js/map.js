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




      //layer control by marike
L.control.layers({
   'Aerial': aerial,
   'mapboxTiles': mapboxTiles,
   'OpenStreetMap':osm,
   }, {}, {
  position: 'topleft'
}).addTo(map);


// Global Javascript 
var lng;
var lat;
var marker = L.marker();
var activeTab="panel1"; // Current active tab


// Locate user and set variables
var ul_latitude;
var ul_longitude;
map.locate({setView: false, watch: false}) 
.on('locationfound', function(e){
    ul_latitude=e.latitude;
    ul_longitude=e.longitude;
}) 


// 
var featureGroup = L.featureGroup().addTo(map);
var drawControl = new L.Control.Draw({
  edit: {
    featureGroup: featureGroup
  },
  draw: {
    polygon: true,
    polyline: false,
    rectangle: true,
    circle: true,
    marker: false
  }
}).addTo(map);
map.on('draw:created', showPolygonArea);
map.on('draw:edited', showPolygonAreaEdited);
function showPolygonAreaEdited(e) {
  e.layers.eachLayer(function(layer) {
    showPolygonArea({ layer: layer });
  });
}

function showPolygonArea(e) {
  featureGroup.clearLayers(); 
  featureGroup.addLayer(e.layer);
  var shape = e.layer.toGeoJSON();
  var shape_for_db = JSON.stringify(shape);
  document.getElementById("ins_spatialexp").value=shape_for_db;
}

/* ShowSpaceExp
 * 
 * Show the space expression layer
 * 
 */
var spaceexp_layer;
function ShowSpaceExp(datas) {
    if(spaceexp_layer!=null) map.removeLayer(spaceexp_layer);
    if(datas!=null && datas!="") {
        spaceexp_layer = new L.geoJson();
        spaceexp_layer.addTo(map);
        spaceexp_layer.addData(jQuery.parseJSON(datas));
    }

}


/* onMapClick
 * 
 * Invoked: Click on the map
 * 
 * @param: e Event
 */
function onMapClick(e) {
   
    // Check if right tab
    var righttab=false;
    if(document.getElementById("panel1").className == "content active") righttab=true;
    if(document.getElementById("panel3").className == "content active") righttab=true;
     
     
    if(righttab) {
       // Set all values to elements    
       lng = e.latlng.lng;
       lat = e.latlng.lat;
       document.getElementById("Latitude").value = lat;
       document.getElementById("Longitude").value = lng;
       document.getElementById("f_Latitude").value = lat;
       document.getElementById("f_Longitude").value = lng;

       // Place the marker
       marker.setLatLng(e.latlng).addTo(map);
       marker.bindPopup('Position for your comment: ' + e.latlng.toString());
       marker.valueOf()._icon.style.opacity = 0.45;
       marker.valueOf()._icon.style.size = "large";

        
       // Set the geo search are
       UpdateCircle(lat, lng,document.getElementById("distance").value);
   }
}

// Connect circle to function
map.on('click', onMapClick);



 
/* CreateMarker
 * 
 * Creates a marker on the map with a popup, containing all informations
 * 
 * Invoked: Every create marker event
 * 
 * @param float x longitude value
 * @param float y latitude value
 * @param string htmltxt content of popup
 * @param int commentid Comment id
 * @param int url_id2
 */   
var markers = []; // Array with all markers
function CreateMarker(x, y, htmltxt, commentid, geo_data) {
     var geo_data='"' + geo_data + '"';
     var marker = L.marker();
     var popup = L.popup();
    
     var markerHtml = htmltxt+" <a href='#' onclick='ShowComment("+commentid+")'> Show more</a>" 
     + " <br> <a href='#' onclick='ShowGeodata("+geo_data+")'>Show Geodata "+geo_data+" </a>";


     var newLatLng = new L.LatLng(x, y);
     marker.setLatLng(newLatLng).addTo(map);
     marker.bindPopup(markerHtml);

     if(markers==null) markers = new Array();
     markers.push(marker);
 }     
    
    
/* UpdateCircle
 * 
 * Displays a blue circle
 * 
 * Invoked: Filter changed
 * 
 * @param float lat position x
 * @param float lng position y
 * @param float distance the radius of the circle
 * @returns {undefined}
 */
var circ;
function UpdateCircle(lat, lng, distance) {
    distance=distance*100;

    if(circ!=null) map.removeLayer(circ);
    circ=null;
    if(distance>0) {
        circ=L.circle([lat,lng], distance); 
        circ.addTo(map);
    }
}


/* ClearMarkers
* 
* Removes all displayed markers in markers array
* 
*/     
function ClearMarkers() {
    if(spaceexp_layer!=null) map.removeLayer(spaceexp_layer);
    if(markers!=null) {
        for(var i=0; i<markers.length; i++) {
            map.removeLayer(markers[i]); 
        }
    }
}

/* ShowMarkers_all
* 
* Displays all markers
* Invoked: At start/tab switched
* 
* @source: ret_getmarkerdatas.php
*/ 
function ShowMarkers_all() {
    ClearMarkers();
    // Submit Post Request
    $.post('php/return/ret_getmarkerdatas.php', "cat=0", function(data){
        var objs = jQuery.parseJSON(data);    
            
        for(var i=0;i<objs.length;i++){
            var obj = objs[i];
            CreateMarker(obj["positionx"], obj["positiony"], obj["content"], obj["id"], obj["geo_data"]);
        }
    });
}

/* ShowMarkersFromJson
* 
* Iterate a json object and display only these markers
* 
* @param: jsonstr   
*/ 
function ShowMarkersFromJson(jsonstr) {
    ClearMarkers();
   
    var objs = jQuery.parseJSON(jsonstr);    

    for(var i=0;i<objs.length;i++){
        var obj = objs[i];
        CreateMarker(obj["positionx"], obj["positiony"], obj["content"], obj["id"]);
    }
}


/* ShowPosition
* 
* Pan to a given position (long/lat)
* 
* @param: positionx targetposition x
* @param: positiony targetposition y   
*/ 
function ShowPosition(positionx, positiony) {
    map.panTo(new L.LatLng(positionx, positiony));
}
        
/* SetFilterUserposition
* 
* Set filter long/lat values to current user position
*   
*/ 
function SetFilterUserposition() {
    $('#f_Latitude').val(ul_latitude);    
    $('#f_Longitude').val(ul_longitude);
    ShowPosition(ul_latitude, ul_longitude);
    
    // Place the marker
    var newLatLng = new L.LatLng(ul_latitude, ul_longitude);
    marker.setLatLng(newLatLng); 

       
}
function ShowGeodata(geo_data){
  //http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs
 
// Add each wms layer using L.tileLayer.wms
var layer = L.tileLayer.wms(geo_data, {
    format: 'img/png',
    transparent: true,
    layer:16 
}).addTo(map).bringToFront();



// var runLayer = omnivore.kml()
//     .on('ready', function() {
//         map.fitBounds(runLayer.getBounds());
//     })
//     .addTo(map).bringToFront;
  
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


/* ShowLayer
*author: marike 
* 
* Show Layer on map by clicking on "showlayer"
* 
* 
*/ 
  
 
   


