'use strict';

//var url = document.getElementById("Url").value;
var url = "http://wfs-kbhkort.kk.dk/k101/wms";
var geodata ;

function GeodataToMap (){

   if(url.contains("WMS") || url.contains ("wms"))

  {
      var mywms = L.tileLayer.wms("http://wfs-kbhkort.kk.dk/k101/wms", {
      layers: 'k101:theme-startkort',
      format: 'image/png',
      transparent: true,
      version: '1.1.0',
    attribution: "myattribution"
});
mywms.addTo(map);

  }




}


                                                             
     