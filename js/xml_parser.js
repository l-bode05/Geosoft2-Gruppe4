'use strict'
// code from http://www.w3schools.com/XML/xml_parser.asp
var geo_data = "http://myserver/cgi-bin/mapserv.exe?map=/ms4w/apps/mymap.map&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetCapabilities"; 

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
        }
}