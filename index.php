<?php
    // Include Database functions
    include 'dbfunctions.php';
    
?>



            
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

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
    <link rel="stylesheet" href="css/mapstyle.css" />
     <!-- If you are using the gem version, you need this only -->
    <link rel="stylesheet" href="css/app.css">
    <script src="js/vendor/modernizr.js"></script>
  
 </head>

<body>





<!--Topbar for Navigation by Marike -->
<nav class="top-bar" data-topbar role="navigation">
  <ul class="title-area" >
    <li class="name">
      <h1><a href="index.php">Geocomment</a></h1>
    </li>
   
   
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar" id="menu-icon" ><a href='#'><span>Menu</span>
       <img src="menu.png" > </a>
       
    
         
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
         
      
     

    <!--Registration//Sign in by Marike, Formular by Tobias-->

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
                  echo file_get_contents("form_register.php");
          }
          ?>

          <!--------------------- End -->

    </div>
  </ul>

    <!-- Left Nav Section -->
    <ul class="left" >
     <strong class="show-for-small-only">
       <li class="has-dropdown">
        <a href="#">Menu</a>
        <ul class="dropdown">
          <li><a href="#">Show all Comments</a></li>
          <li><a href="#">Filter</a></li>
          <li><a href="#">New Comment</a></li>
          <li><a href="#">Advanced Search</a></li>
          <li><a href="Help.html">Help</a></li>
          <li><a href="impressum.html">Impressum</a></li>

        </ul>
      </li>
      </strong>
   
    
   
     
          
      <!--Search Bar-->
      <li class="has-form" id="firstStop">
       <div class="row collapse">
         <div class="large-10 small-10 columns">
            <input type="text" placeholder="Find Geodata">
         </div>
        <div class="large-2 small-2 columns">
         <a href="#" class="tiny button">Search</a>
        </div>
       </div>
     </li>
    </ul>

    <ul class="right">
     <!-- right Nav Section -->
       <!-- information stuff only for large -->
       <strong class="show-for-medium-up">
       <li class="has-dropdown ">

       <img src="info-icon-32.png" width="36px" height="36px" bottom="1px">
      
       
      <ul class="dropdown">
          <li><a href="Help.html">Help</a></li>
          <li><a href="Impressum.html">Impressum</a></li>
       
        

        
     </li>
     </strong>
    </ul>
    
  </section>
</nav>








  


<div class="rows">

     
  <div id="map">
   
   
      <!--javascript for mapfunctions-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script>
        // Generate Markers
        $(document).ready(function(){
            <?php
                $res=GetAllFirstComments();
                for($i=0; $i < count($res); $i++) {
                    $answers_num=count(GetCommentThread($res[$i]["id"]));
                    echo "CreateMarker(".$res[$i]["positionx"]." , ".$res[$i]["positiony"]." , '"
                            .$res[$i]["title"]."<br>".$res[$i]["content"]."<br>Comments: $answers_num' , '".$res[$i]["id"]."'); "; // Marker txt
                }
             ?>
        });
    </script>  
    
    <!--javascript for map function-->
    <script src="map.js"></script>
 

    }
 
  
  </div>




<!--map and comments part by marike-->

  <div class="small-6 medium-6 large-6 columns">
  <!--map -->
 
  </div>

  <div class="small-5 hide-for-small medium-5 hide-for-medium  large-5 columns" id="backgroundright" >
      <dl class="tabs hide-for-small" data-tab >
          <dd> <a  href="#panel1" id="secondStop">Home</a></dd>
          <dd class="active"> <a href="#panel2" id="thirdStop">Comments  </a></dd>
          <dd> <a  href="#panel3" id="stop4">Filter</a></dd>
      </dl>
      
    <div class="tabs-content">
      <div id="panel1" class="content" >
        <p> Welcome to Geocomment </p>
         


<div class="large-12 columns" >

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
		  
		  
         
       
  </div>
        <div class="content active"  id="panel2">
<form name="Showcomments" action="http://giv-geosoft2d.uni-muenster.de" method="get">
<fieldset>
	<legend>Comments so far:</legend>


</fieldset>
</form>
  
        </div>


       <div class="content" id="panel3">
	
      <!--  <form name="Filter" action="http://de.selfhtml.org/cgi-bin/formview.pl" method="post" onsubmit="return chkFormular2()">
          
        -->
            <h4>Filter</h4>

            <p> Year </p>
          <div class="range-slider radius" data-slider data-options="start:1960 ; end:2015;">
			  <span class="range-slider-handle" role="slider" tabindex="0"></span>
			  <span class="range-slider-active-segment"></span>
			  <input type="hidden">
		  </div>

		   <p> Radius in km </p>
		 <div class="range-slider" data-slider data-options="step: 10;">
			  <span class="range-slider-handle" role="slider" tabindex="0"></span>
			  <span class="range-slider-active-segment"></span>
			  <input type="hidden">
		</div> 
			<div class="switch round tiny">
			  <input id="exampleRadioSwitch3" type="radio" name="testGroup">
			  <label for="exampleRadioSwitch3"></label>
			</div>
		</div>

       
       

        </div>
       

        
        
        
        
        <div class="content" id="panel_showcomment">
	  <div id="showncomments"> </div>
        </div>

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
<ol class="joyride-list" data-joyride>
	<li data-id="firstStop" data-text="Next" data-options="tip_location: bottom; nub_position: relative; prev_button: false">
	<h5> Welcome ! </h5>
    <p>Hello and welcome on Geocomment. On Login you can login or just click on the Map to start comment.</p>
  </li>
<li data-id="secondStop" data-text="Next" data-options="tip_location: top; " data-prev-text="Prev">
	 <h6>New comment</h6>
    <p>Here you can leave a new comment. Choose a position for your geodata on map.</p>
  </li>
  <li data-id="thirdStop" data-class="custom so-awesome" data-text="Next" data-prev-text="Prev">
    <h6>See comments</h6>
    <p>See all comments sorted by time.</p>
  </li>
  <li data-id="stop4" data-button="Next" data-prev-text="Prev" data-options="tip_location:top;tip_animation:fade">
    <h6>Filter</h6>
    <p> Data can filter by time and location</p>
  </li>
  <li  data-button="Next"  data-prev-text="Prev" data-options="tip_location:top;tip_animation:fade">

    <h6>More help</h6>
    <p>If you are not sure how to use Geocomment, have a look on FAQ. </p>
     <a href="Help.html" class="tiny round button">Help</a>
     <br>
     <br>
  </li>
  <li data-button="End" data-prev-text="Prev">
    <h4>Have fun</h4>
    <p></p>
  </li>
</ol>




  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.interchange.js"></script>
  <script src="js/foundation/foundation.offcanvas.js"></script>
  <script src="js/foundation/foundation.joyride.js"></script>
  <script src="js/foundation/foundation.slider.js"></script>
  <script src="js/vendor/jquery.cookie.js"></script> <!-- Optional -->
  <script>
    $(document).foundation();
    
   
      $(document).foundation().foundation('joyride', 'start');
   
  </script>





</body>
</html>
