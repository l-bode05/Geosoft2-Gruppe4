<?php
    // Include Database functions
    include 'dbfunctions.php';
    
?>
 
            
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
    
<!-- Import JQUERY framework and css -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="all"
  href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css"    />

        
<head>
    <meta charset="utf-8">
    <!-- If you delete this meta tag World War Z will become a reality -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marike Meijer">
    <title>Geocomment</title>

    <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/foundation.css">
    <!-- Karte-->
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />




<!--<style>
  body { margin:0; padding:0; }
  #map { position:absolute; top:0; bottom:0; width:100%; }
</style> 
-->

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="css/app.css">

  <script src="js/vendor/modernizr.js"></script>

    
</head>

<body>





<!--Topbar for Navigation by Marike -->
<nav class="top-bar" data-topbar role="navigation">
  <ul class="title-area">
    <li class="name">
      <h1><a href="index.php">Geocomment</h1>
    </li>
   </ul>
   <ul class="right-area hide-for-large">
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon hide-for-large" style="position:right" id="menu-icon" >
       <img src="menu.png" >
       <style type="text/css">
          #menu-icon
          {
            position:right;
          }
       </style>
    
         </a>
    </li>
  </ul>

  <section class="top-bar-section">

    <!-- Middle Nav Section -->
    <ul class="middle">
                
         
           
          
      
     

        <!-- Start Login Formular--------------------------- -->
       <?php 

        // Only show form to unloggedin visitors
        if(IsLoggedIn() == true) {
            echo file_get_contents("form_logout.php");   
        } else {
            echo file_get_contents("form_login.php");
        }
       ?>

       <!--------------------- End -->
         
      
     


    <!-- <li><a href="#" data-reveal-id="sign-in">Sign In</a></li>-->
    
     <div id="sign-in" class="reveal-modal" data-reveal>
         <h4> Sign In</h4>

         <script src="signin.js"></script>

        <!-- Start Register Formular--------------------------- -->
          <?php 
            // Only show form to unloggedin visitors
            if(IsLoggedIn() == true) {
                echo "Already registered.";
            } else {
                echo requireToVar("form_register.php");
            }
          ?>

          <!--------------------- End -->

    </div>
  </ul>

    <!-- Left Nav Section -->
    <ul class="left" >
    
      <li class="reveal-modal">
      <h3>menu</h3>
            

      </li>
          

      <li class="has-form">
       <div class="row collapse">
         <div class="large-12 small-10 columns">
            <input type="text" placeholder="Find Geodata">
         </div>
        </div>
     </li>


    </ul>
  </section>
</nav>








  


<div class="rows">
<!--<style>
#rows {
width: 100%;
margin-left: auto;
margin-right: auto;
margin-top: 0;
margin-bottom: 0;
max-width: 100%;

}
</style>
<div id="swap" data-interchange="[../small-header.html, (only screen)], [../medium-header.html, (medium)]">
</div> -->
  

     
  <div id="map">
    <style>

     #map{
    

    width: 100%; 
    height: 95%;
    float: left;
    position: absolute;
    }
    
    @media screen and (min-width: 1025px) {
      #map{
      width:58%;
      height:95%;

          }
    }
     
    </style>
   
      <!--javascript for mapfunctions-->
    <script>
        // Generate Markers
        $(document).ready(function(){
            ShowMarkers_all();
            
            <?php 
                if($_GET['comment']!="") { // Permalink to comment
                    echo 'ShowComment('.GetCommentIdByLink($_GET['comment']).");";
                } elseif($_GET['tag']!="") { // Permalink to tags
                    echo "ShowCommentsToTag('".$_GET['tag']."');";
                } elseif($_GET['user']!="") { // Permalink to tags
                    echo "ShowUserProfile('".$_GET['user']."');";
                }
            ?>
        });
    </script>  
    
    <script src="map.js">
    </script>


   
  

    
    }

  <!--

  <style>
 
  #map {
    width: 59% ; 
    height: 93%;
    float: left;
    position: absolute;

  }

  </style>
  -->
  
<!--
  <script type="text/javascript">
 
  var map = L.map('map').setView([51.96217, 7.62561],12);
 
  L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 35,
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    }).addTo(map);

  
  
  
  var popup = L.popup();
  
  

  </script>
  -->
  </div>






  <div class="small-6 medium-6 large-6 columns">
  <!--map -->


  
  
  </div>

  <div class="small-5 hide-for-small medium-5 hide-for-medium  large-5 columns" id="backgroundright" >
      <dl class="tabs hide-for-small" data-tab >
          <dd> <a  href="#panel1" onclick="ShowMarkers_all()">Home</a></dd>
          <dd class="active"> <a href="#panel2" onclick="ShowMarkers_all()">Comments</a></dd>
          <dd> <a  href="#panel3">Filter</a></dd>
      </dl>
      
    <div class="tabs-content">
      <div id="panel1" class="content" >
        <p> Welcome to Geocomment </p>
          <!-- Size Classes: [tiny small large] -->
          <!-- Radius Classes: [radius round] -->
          <!-- Color Classes: [secondary success alert] -->
          <!-- Disabled Class: disabled -->


<div class="large-12 columns">

  <!--
  <a href="comment.html" class="button round" id="butcom1">New Comment</a>
  -->

<button class="button" data-dropdown="newcom"  id="newcom2" aria-controls="newcom">New Comment</button>



<script src="createcomment.js"></script>

 <!-- Start Create Comment Formular--------------------------- -->
<?php 

    include "form_insertcomment.php";

?>
<!--------------------- End -->


        
    
    </div>
		  
		  
          <style>
            #butcom1 {

               width: 50% ; 
               left: 14%;
               
               background-color: blue;
                
                float: left;
                position: left;

            }
          </style>
        
        </div>
        <div class="content active"  id="panel2">
        <form name="Showcomments" action="http://giv-geosoft2d.uni-muenster.de" method="get">
            <fieldset>
                <legend>Last comments</legend>


                <?php
                // Display the 5 newest comments
                 $res=GetAllFirstComments();
                 for($i=0; $i < Min(5,count($res)); $i++)  {
                        $html=$html.
                                "<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
                                      ")' > Goto </a> <br>";
                        $html=$html.
                                "<a href='#' onclick='ShowComment(".$res[$i]["id"].")' > Show Thread </a> <br>";
                        $html=$html.GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
                        $html=$html.$res[$i]["timecreated"]."<br>";
                        $html=$html.$res[$i]["title"]."<br>";
                        $html=$html.$res[$i]["content"]."<br>";
                        $html=$html."<a href='/steffen/index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
                        $html=$html.TagsIdsToName($res[$i]["tags_ids"])."<br>";
                        $html=$html."<br><br><br>";
                 }

                 echo $html;
                ?>

        </fieldset>
        </form>

        </div>
        <div class="content" id="panel3">
	<html>

       
      
        <!-- Start Create Comment Formular--------------------------- -->
        <?php 

           include "form_filter.php";

        ?>
        <!--------------------- End -->
  </div>
        <div class="content" id="panel_showcomment">
	  <div id="showncomments"> </div>
        </div>

    </div>
  </div>
  </div>
  
</div>







<!--Footer 

<footer class"row">
  <div class="large-12 columns">
    <div class="row">
      <div class="large-8 columns">
        
       
        </div>
      <div class="large-4 columns">
       
        <p>links and more</p>
        <style type="text/css">
        #type  {
        height:100%;
        
         position: right;
        }

        </style>
      </div>
    </div>
  </div>
</footer>
-->

  <!-- body content here -->
<!--Joyride Foundation Marike-->
<!-- At the bottom of your page but inside of the body tag -->


  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.interchange.js"></script>
  <script src="js/foundation/foundation.offcanvas.js"></script>
  <script src="js/foundation/foundation.joyride.js"></script>
  <script>
    $(document).foundation();
    
   
      $(document).foundation('joyride', 'start');
   
  </script>





</body>
</html>