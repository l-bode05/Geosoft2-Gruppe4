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


          	//layer control

         L.control.layers({
             'Aerial': aerial,
             'mapboxTiles': mapboxTiles,
             'OpenStreetMap':osm,
             }, {}, {
            position: 'topleft'
          }).addTo(map);
               
           //locate user 
            
           map.locate({setView: true , maxZoom: 14});
              
     	
      var marker = L.marker();
			var popup = L.popup();
			var markerHtml = 'Leave Comment here?';
      var lng;
      var lat;

			
				//comment on map
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
             marker
              .setLatLng(e.latlng)
              .addTo(map);
                
               marker.bindPopup(markerHtml + e.latlng.toString());
           
			}



       map.on('click', onMapClick);

    
        
        

        