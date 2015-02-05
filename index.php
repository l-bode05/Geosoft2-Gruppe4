<?php
    // Include Database functions
    include 'php/database/dbfunctions.php';
    setcookie('welcomehelp', $_COOKIE["welcomehelp"]+1, strtotime("+1 month"), "/", ".uni-muenster.de" );
     

?>
<!DOCTYPE HTML PUBLIC>
<html class="no-js" lang="en" >
<body>
     
<!-- Include Stylesheet -->
<head>
    <title>Geocomment</title>

    <meta charset="utf-8">
    <!-- If you delete this meta tag World War Z will become a reality -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marike Meijer">

    <!-- Import JQUERY framework and css -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
    
    <!-- Import Tagbox framework and css -->
    <script src="js/tagbox/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
    <link href="js/tagbox/css/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="js/tagbox/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
    
    <!-- Import RateIT framework and css -->
    <script src="js/rateit/jquery.rateit.min.js" type="text/javascript" charset="utf-8"></script>
    <link href="js/rateit/rateit.css" rel="stylesheet" type="text/css">
    
    
     <link rel="stylesheet" type="text/css" media="all"
           charset=""href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css"/>

     <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
     <link rel="stylesheet" href="css/normalize.css">
     <link rel="stylesheet" href="css/foundation.css">
     <!-- Karte-->
     <link rel="stylesheet" href="css/leaflet.css" />
     <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
     <!-- Bring in the  KML plugin -->
    
     <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.2.0/leaflet-omnivore.min.js'></script>   
      
     <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
     <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
     <link rel="stylesheet" href="css/mapstyle.css" />
     <link rel="stylesheet" href="css/style.css" />
     <!-- If you are using the gem version, you need this only 
     <link rel="stylesheet" href="css/app.css">-->
     <script src="js/vendor/modernizr.js"></script>
     
     <link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css' rel='stylesheet' />
     <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js'></script>
     <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js'></script>

     
     <script src="js/jquery.xml2json.js" type="text/javascript" language="javascript"></script>
 </head>

<!--- Javascript init functions -->
<script>
    // Load Tags
    var str='<?php echo GetTagsListed(); ?>';
    var usedTags = str.split(",");
    
    
    // Generate Markers
    $(document).ready(function(){
        ShowMarkers_all();
  
        <?php
            // Ask for get Querys/Permalinks
            if($GLOBALS["conn"]->real_escape_string($_GET['comment'])!="") { // Permalink to comment
                echo 'ShowComment('.GetCommentIdByLink($GLOBALS["conn"]->real_escape_string($_GET['comment'])).");";
            } elseif($GLOBALS["conn"]->real_escape_string($_GET['tag'])!="") { // Permalink to tags
                echo "ShowCommentsToTag('".$GLOBALS["conn"]->real_escape_string($_GET['tag'])."');";
            } elseif($GLOBALS["conn"]->real_escape_string($_GET['user'])!="") { // Permalink to tags
                echo "ShowUserProfile('".$GLOBALS["conn"]->real_escape_string($_GET['user'])."');";
            } else {
                // Else zoom to user position
                echo "map.locate({setView: true , maxZoom: 13});";
            }
        ?>
        
        
       
    });
    
    

</script>


<!--Topbar for Navigation by Marike -->
<div class="fixed">
<nav class="top-bar"  data-topbar role="navigation"  id="top-bar">
<ul class="title-area" >
	<li class="name">
	 
	  <a href="index.php"><img src="img/ZakbumLogoFinal.png" id="zakbum"></a>
	</li>

   
	 <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
	<li class="toggle-topbar" id="menu-icon" ><a href='#'><span>Menu</span>
	   <img src="img/menu.png" > </a>
	</li>
</ul>

	
	
	
<section class="top-bar-section">
    <!-- Middle Nav Section -->
    <ul class="middle">

        <!-- Start Login Formular--------------------------- -->
       <?php 

        // Only show form to unloggedin visitors
        if(IsLoggedIn() == true) {
            echo file_get_contents("php/form/form_logout.php");   
        } else {
            echo file_get_contents("php/form/form_login.php");
        }
       ?>

       <!--------------------- End -->
         
     

    <!-- <li><a href="#" data-reveal-id="sign-in">Sign In</a></li>-->
    
     <div id="sign-in" class="reveal-modal" data-reveal>
        

        <!-- Start Register Formular--------------------------- -->
          <?php 
            // Only show form to unloggedin visitors
            if(IsLoggedIn() == true) {
                echo "Already registered.";
            } else {
                echo requireToVar("php/form/form_register.php");
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
          <li><a href="help.html">Help</a></li>
          <li><a href="impressum.html">Impressum</a></li>
        </ul>
      </li>
      </strong>
   
      <!--Search Bar-->
      <li class="has-form" id="firstStop">
       <div class="row collapse">
         <div class="large-10 small-10 columns">
            <input type="text" placeholder="Find Geodata" id="tbs_keyword">
         </div>
        <div class="large-2 small-2 columns">
         <a href="#" class="tiny button" onclick="TextboxSearch();">Search</a>
        </div>
       </div>
     </li>
    </ul>

    <ul class="right">
     <!-- right Nav Section -->
       <!-- information stuff only for large -->
       <strong class="show-for-medium-up">
       
       <li class="has-dropdown">

       <img src="img/info-icon-32.png" id="infoicon">
       
       <ul class="dropdown">
          <li><a href="help.html">Help</a></li>
          <li><a href="impressum.html">Impressum</a></li>
       </ul>
       </li>
     </strong>
    </ul>
    
  </section>
</nav>
</div>



<!--Help Button-->
<div class="rows">
  <div id="map">
   <link rel="stylesheet" href="css/mapstyle.css">
      
   <a href="help.html"><img src="img/icon-help.png" id="icon-help"></a>

      
    <!-- Include Javascript librarys -->
    <script src="js/map.js"></script>
    <script src="js/element_functions.js"></script>

   
  </div>




<!--map and comments part by marike-->

<div class="small-6 medium-6 large-6 columns"></div>

<div class="small-5 hide-for-small medium-5 hide-for-medium  large-5 columns" id="backgroundright" >

<!--Three big panel links-->
<dl class="tabs hide-for-small" data-tab >
  <dd> <a  href="#panel1" onclick="ShowMarkers_all();" id="secondStop">Create Comment</a></dd>
  <dd class="active"> <a href="#panel2" onclick="ShowMarkers_all()" id="thirdStop">Show Comments  </a></dd>
  <dd> <a  href="#panel3" id="stop4">Search</a></dd>
</dl>
  
<div class="tabs-content">
    <div id="panel1" class="content" >

		<div class="large-12 columns" >

	
		<!-- Start Create Comment Formular--------------------------- -->
		<?php 
			include "php/form/form_insertcomment.php";
		?>
		<!--------------------- End -->
		   
	   </div> 
   </div> 

   
   
   
   
   <!-- TAB COMMENTS-->
    <div class="content active"  id="panel2">
        <form name="Showcomments" action="http://giv-geosoft2d.uni-muenster.de" method="get">
			<fieldset>
			  <legend>Last comments</legend>

				 <?php
					 // Display the 5 newest comments
					 $res=GetAllFirstComments();
					 for($i=0; $i < Min(5,count($res)); $i++)  {
							$html=$html.'<b>Show Position: </b>'.
									"<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
										  ")' > Goto </a> <br>";
							$html=$html.
									"<a href='#' onclick='ShowComment(".$res[$i]["id"].")' > Show Thread </a> <br>";
							$html=$html.'<b>User: </b>'.GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
							$html=$html.'<b>Date: </b>'.$res[$i]["timecreated"]."<br>";
							$html=$html.'<b>Title: </b>'.$res[$i]["title"]."<br>";
							$html=$html.'<b>Comment: </b>'.$res[$i]["content"]."<br>";
							$html=$html."<a href='index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
							$html=$html.'<b>Tags: </b>'.TagsIdsToName($res[$i]["tags_ids"])."<br>";
							$html=$html."<hr><br>";
					 }

					 echo $html;
					?>

			</fieldset>
        </form>
	</div> 

	<!--TAB FILTER-->
	<div class="content" id="panel3">
		<!-- Start Create Comment Formular--------------------------- -->
			<?php
			   include "php/form/form_filter.php";
			?>
		<!--------------------- End -->
	</div>

    
        
      
      <!--SHOW COMMENTS Part--> 
        
    <div class="content" id="panel_showcomment">
	   <div id="showncomments"> 
         </div>
	</div>
</div>
            
  

</div> 
</div> 

<!--Devices View-->
<div class="small-12 medium-12 columns hide-for-large" id="bottomsmart">
<strong class="hide-for-large-up">


<!-- Also works with <dl>'s and <dt>'s in place of <ul>'s and <li>'s. --> 

<dl class="accordion" data-accordion>
  <dd class="accordion-navigation">
    <a href="#panel1b">Create comment</a>
    <div id="panel1b" class="content active">
    

    <!-- Start Create Comment Formular--------------------------- -->
    <?php 

    include "php/form/form_insertcomment.php";

    ?>
    <!--------------------- End -->
    </div>
  </dd>
  
  <dd class="accordion-navigation">
    <a href="#panel2b">Show Comments</a>
    <div id="panel2b" class="content">
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
                        $html=$html."<a href='index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
                        $html=$html.TagsIdsToName($res[$i]["tags_ids"])."<br>";
                        $html=$html."<br><br><br>";
                 }

                 echo $html;
                ?>

        </fieldset>
        </form>
     </div>
  </dd>
  <dd class="accordion-navigation">
    <a href="#panel3b">Search</a>
    <div id="panel3b" class="content">
     <!--TAB FILTER-->
       

            <!-- Start Search Formular--------------------------- -->
        <?php 

           include "php/form/form_filter.php";

        ?>
        <!--------------------- End -->
          
       
        </div> 
       
     
        
      
     </div>
  </dd>
</dl>

      
</strong>

</div>










  
<!--Joyride Foundation-->


<!-- At the bottom of your page but inside of the body tag -->
<?php


if($_COOKIE["welcomehelp"] < 1) {
    echo '
    <ol class="joyride-list" data-joyride id="geo">
            <li data-id="firstStop" data-text="Next" data-options="tip_location: bottom; nub_position: relative; prev_button: false">
             <h5> Welcome to Geocomment ! </h5>
            <p>This is a website to comment on geodata. You can login or sign in here.</p>
      </li>
    <li data-id="secondStop" data-text="Next" data-options="tip_location: top; " data-prev-text="Prev">
              <h6>New comment</h6>
        <p>Leave a new comment. Choose a position for your geodata and just click on a map.</p>
      </li>
      <li data-id="thirdStop" data-class="custom so-awesome" data-text="Next" data-prev-text="Prev">
        <h6>All comments</h6>
        <p>See all comments sorted by time.</p>
      </li>
      <li data-id="stop4" data-button="Next" data-prev-text="Prev" data-options="tip_location:top;tip_animation:fade">
        <h6>Search</h6>
        <p> Data can filter by time and location or rated geodata</p>
      </li>
      <li data-id="showncomments" data-button="Next" data-prev-text="Prev" data-options="tip_location:top;tip_animation:fade">
        <h6>Show Comments</h6>
        <p> Here are all comments</p>
      </li>
      <li  data-button="Next"  data-prev-text="Prev" data-options="tip_location:top;tip_animation:fade">

        <h6>More help</h6>
        <p>If you are not sure how to use Geocomment, have a look on our Help. </p>
         <a href="help.html" class="tiny round button">Help</a>
         <br>
         <br>
      </li>
      <li data-button="End" data-prev-text="Prev">
        <h4>Have fun</h4>
        <p></p>
      </li>
    </ol> ';

        
} 
?>


    <!-- Scripts to start foundation stuff -->
  
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.interchange.js"></script>
  <script src="js/foundation/foundation.joyride.js"></script>
  <script src="js/vendor/jquery.cookie.js"></script> 
 
  <script src="js/foundation/foundation.accordion.js"></script>
  <script>
   
    $(document).foundation();
    
   
    $(document).foundation().foundation('joyride', 'start');
   
  </script>

  


</body>
</html>