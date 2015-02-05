'use strict';

var geo_data='"' + geo_data + '"';

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




      //layer control 
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


/* showPolygonAreaEdited
 * 
 * Event after edited a polygon
 * 
 */
map.on('draw:edited', showPolygonAreaEdited);
function showPolygonAreaEdited(e) {
  e.layers.eachLayer(function(layer) {
    showPolygonArea({ layer: layer });
  });
}

/* showPolygonArea
 * 
 * Event after created a polygon
 * 
 */
map.on('draw:created', showPolygonArea);
var edit_layer;
function showPolygonArea(e) {
  if(spaceexp_layer!=null) map.removeLayer(spaceexp_layer);
  featureGroup.clearLayers(); 
  featureGroup.addLayer(e.layer);
  edit_layer=e.layer;
  var shape = e.layer.toGeoJSON();
  var shape_for_db = JSON.stringify(shape);
  document.getElementById("ins_spatialexp").value=shape_for_db;
  document.getElementById("spatialexp_status").value="Set. Manually created.";
   
  // Set for filter
  var selected = new Array();
  selected=shape_for_db.split("[[[");
 
  if(selected.length>0) {
	shape_for_db=shape_for_db.split("[[[")[1];
	var points1=shape_for_db.split("]")[0];
	
	selected=shape_for_db.split("[");
	
	if(selected.length>1) {
		  var points2=shape_for_db.split("[")[2].split("]")[0];
		  document.getElementById("bbox").value=points1.split(",")[1]+" "+points1.split(",")[0]+" "+points2.split(",")[1]+" "+points2.split(",")[0];
	}
  }
 
}

/* ShowSpaceExp
 * 
 * Show the space expression layer
 * 
 */
var spaceexp_layer;
function ShowSpaceExp(datas) {
    if(spaceexp_layer!=null) map.removeLayer(spaceexp_layer);
	if(edit_layer!=null) map.removeLayer(edit_layer);
	featureGroup.clearLayers(); 
	
    if(datas!=null && datas!="") {
        spaceexp_layer = new L.geoJson();
        spaceexp_layer.addTo(map);
        spaceexp_layer.addData(jQuery.parseJSON(datas));
		map.fitBounds(spaceexp_layer.getBounds());
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
 * @param string geo_data
 */   
var markers = []; // Array with all markers
function CreateMarker(x, y, htmltxt, commentid, geo_data) {
     var geo_data='"' + geo_data + '"';
     var marker = L.marker();
     var popup = L.popup();   
     var markerHtml = htmltxt+" <a href='#' onclick='ShowComment("+commentid+")'> Show more</a>" 
     + " <br> <a href='#' onclick='ShowGeodata(" +geo_data+")'>Show Geodata " +geo_data+ "</a>";

  /*   var markers = []; // Array with all markers
function CreateMarker(x, y, htmltxt, commentid, geo_data) {
     geo_data='"' + geo_data + '"';
     var marker = L.marker();
     var popup = L.popup();
     var url_id= "http://gis.srh.noaa.gov/arcgis/services/NDFDTemps/MapServer/WMSServer";
     var markerHtml = htmltxt+" <a href='#' onclick='ShowComment("+commentid+")'> Show more</a>" 
     + " <br> <a href='#' onclick='ShowGeodata("+geo_data+")'>Show Geodata  </a>";
*/

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


/*//get xml from url
 function loadXMLDoc(geo_data){
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    }
    else {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.open("GET", geo_data, false);
    xhttp.send("");
    return xhttp.responseXML;
}

//display xml 
function displayResult(){
  // a URL can replace a local xml file, but the script has to be on the same domain
  // due to security
    //"http://myserver/cgi-bin/mapserv.exe?map=/ms4w/apps/mymap.map&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetCapabilities"
    xml = loadXMLDoc("GetCapabilities.xml");
    
    xsl = loadXMLDoc("GetCapabilities.xsl");
    // code for IE
    if (window.ActiveXObject) {
        ex = xml.transformNode(xsl);
        document.getElementById("GetCapabilitiesResults").innerHTML = ex;
    }
    // code for Mozilla, Firefox, Opera, etc.
    else 
        if (document.implementation && document.implementation.createDocument) {
            xsltProcessor = new XSLTProcessor();
            xsltProcessor.importStylesheet(xsl);
            resultDocument = xsltProcessor.transformToFragment(xml, document);
            document.getElementById("GetCapabilitiesResults").appendChild(resultDocument);
        
               alert(resultDocument);
        }
      }*/

       


function ShowGeodata(geo_data){
  //http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs
 //var geo_data='"' + geo_data + '"';

 //http://geoserver.itc.nl/cgi-bin/mapserv.exe?map=D:/Inetpub/mapserver
 //pg_config.map&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&SRS=EPSG:28992&BBOX=248500,464700,263900,478600&FORMAT=image/png&WIDTH=616&HEIGHT=556&LAYERS=enscadparcels
 //alert(geo_data);
 var type = decodeURI(geo_data);

 /*
        var xmlhttp; 
        var xmlDoc;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.open("GET", geo_data, false);
        xmlhttp.send();
        xmlDoc=xmlhttp.responseXML;

         alert("xml "+ xmlDoc);*/
     
var url = geo_data;
var xml ;
var jsonp;
  






 /*   function getLayer(xml) {
  var layers_from_xml = xml.getElementsByTagName("Layer")[0];
  if (layers_from_xml) {
    var layers_from_xmlNodes = layers_from_xml.childNodes;
    if (layers_from_xmlNodes) {
      for (var i = 0; i < layers_from_xmlNodes.length; i++) {
        var name = layers_from_xmlNodes[i].getAttribute("Name");
        //var colour = fruitsNodes[i].getAttribute("colour");
        alert("Layer " + layers_from_xmlNodes + " Layer " + layers_from_xml);
      }
    }
  }
}*/
     

    
     
    /* if (layers_aus_xml){
      var layers_aus_xmlNodes = layers_aus_xml.childNodes;
      if (fruitsNodes){
        for (var i=0; i < layers_aus_xmlNodes.lenght; i++) {
          var name = layers_aus_xmlNodes[i].getA
        }
      }
     }*/
    
  //check if url is a wms
 if(type.indexOf("WMS") !=-1|| type.indexOf("wms") !=-1){
   //alert("WMS");
// ajax get 

  $.ajax({
      type: "GET",
      url: url + "?REQUEST=GetCapabilities&VERSION=1.0.1&SERVICE=WMS",
      dataType: "xml",
      success: function(xml){
      console.log(xml);
      var jsonp = $.xml2json(xml);
      console.log(jsonp);
      return jsonp;
      return xml;

    }

      
    

    });

    //add wms to map using L.tileLayer.wms
 	var layer = L.tileLayer.wms(url, {
       format: 'img/png',
       transparent: true,
       layers: 16
    }).addTo(map).bringToFront();

   

    


}

 else 
  //check if url is KML
  if(type.indexOf("KML")!=-1 || type.indexOf("kml")!=-1){
  	//alert("KML");

//http://harrywood.co.uk/maps/examples/leaflet/mapperz-kml-example.kml
 //http://web-apprentice-demo.craic.com/assets/maps/fremont.kml

  var url = geo_data;
  $.ajax({
      type: "GET",
      url: url,
      dataType: "xml",
      success: function(xml){
      console.log(xml);
      var jsonp = $.xml2json(xml);
      console.log(jsonp);
      return jsonp;
      return xml;

    }

      
    

    });

    var str = type;
    var n = str.lastIndexOf( "/");
    var m = str.lastIndexOf( ".kml");
    var kmls = str.substring(n+1,(m+4));
   // alert(kmls);

 var runLayer = omnivore.kml(kmls)
     .on('ready', function() {
         map.fitBounds(runLayer);
     })
     .addTo(map).bringToFront;
  }

  else 

    if(type.indexOf("GML")!=-1 || type.indexOf("gml")!=-1){
      //alert("GML");

    

    }
    else
console.log("type not defined");


  
}



 
 


  /* $.ajax({
      type: "GET",
      url: geo_data ,
      dataType: 'WMS',
      success: function (response) {

        alert(response);

        geojsonLayer = L.geoJson(response, {
            transparent:true,
        }).addTo(map);
}
})*/