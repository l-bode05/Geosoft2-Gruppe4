

 
<?php

/**
 * General functions
 * 
 * 
    
    // ToDo:
    FilterGeoData(Time1, time2, boundingbox)
    UpdateComment()
    GetAutocompleteWords(prefix);
 */

    include("dbhelpfunctions.php");

    include("dbconnect.php");

    /**
    * Insert a new comment, only with geodatalink
    * 
    * @param int id of the unique comment
    * @param string $content Comment content
    * @param string $title Comment Title
    * @param string $resources Comment resources
    * @param string $geodata_link Geodatalink for comment
    * @param string $user_id Id of user who created this comment. Add [anonym] for anonym comments
    * @param string $taglist taglist("tag1,tag2,..,") of comment
    * @param float $pointx, $pointy Position of comment
    * @return int statuscodes: -1 = Nickname already taken for anonym, 1 = Success
    */
    function InsertComment($answer_to, $content, $title, $resources, $geodata_link, $user_id, $taglist, $pointx, $pointy) {
        // GeoDatabase already existis
        if(GeoDataLinkExists($geodata_link)) {
            $id=GetSelectField("SELECT * FROM geodata WHERE url='$geodata_link'", "id");
            echo trim(GetSelectField("SELECT * FROM comments WHERE geodata_id=$id", "permalink"));
            return;
        }
        
        // Check for anonym comment
        if($user_id=="anonym") {
            $user_id=0;
            
            // Add random Number and save
            $rand=rand(99999, 999999);
            if($_COOKIE['anonymrand']!="") $rand=$_COOKIE['anonymrand'];
            $user_id.=$rand;
            setcookie('anonymrand', $rand, strtotime("+1 month"));
        } 
        
        // Convert TagNameString to TagIDString,
        $taglist=TagsNameToId($taglist);

        // Permalink from title
        $permalink=StrToPermalink($title, "comments");

        // Convert geodata_link to id
        $geodata_id=GeoLinkToId($geodata_link);

        
        // Finally insert new comment
        $sql = "INSERT INTO comments (answer_to, content, title, resources, geodata_id, user_id, tags_ids, permalink, positionx, positiony) "
                . "VALUES ('".$answer_to."','".$content."','".$title."','".
                        $resources."','".$geodata_id."','".$user_id."','".$taglist."','".$permalink.
                            "','".$pointx."','".$pointy."');";

        //GeomFromText( 'POINT(".$pointx." ".$pointy.")' )
        
        RequestInsert($sql);
        echo "1";
    }

    /**
    * InsertCommentGeodata
    * 
    * Extension of 'InsertComment'. Add geodata values directly
    * @param datestamp $datestamp Date of geodata
    * @param ? $boundingbox Boundingbox of geodata
    */ 
    function InsertCommentGeodata($answer_to, $content, $title, $resources, $geodata_link, $user_id, $taglist, $pointx, $pointy, $datestamp, $boundingbox, $spatialexp) {
            InsertComment($answer_to, $content, $title, $resources, $geodata_link, $user_id, $taglist,$pointx, $pointy);
            SetGeodataDetails($geodata_link, $datestamp, $boundingbox, $spatialexp);
    }

    
    /**
    * RateGeodata
    * 
    * Process a new rating to a geodata
    * @param string $geodata_idorlink link or id of a geodata
    * @param int new rating
    */
    function RateGeodata($geodata_id, $rating) {
        if(CanRateGeodata($geodata_id)==FALSE) exit("Can't rate this geodata");
        
        RequestUpdate("UPDATE geodata SET ratings_sum=ratings_sum+".$rating.", ratings_count=ratings_count+1 WHERE id='$geodata_id'");
    
        // Set Cookie
        setcookie('rated'.$geodata_id, "true", strtotime("+1 month"));
    }
    
    
     /**
    * GetAverageRating
    * 
    * Get ratings and calculate the average
    * @param string $geodata_idorlink link or id of a geodata
    * @return float Average rating with two decimal places
    */
    function GetAverageRating($geodata_id) {
        $ratings_sum=GetSelectField("SELECT * FROM geodata WHERE id=".$geodata_id, "ratings_sum");
        $ratings_count=GetSelectField("SELECT * FROM geodata WHERE id=".$geodata_id, "ratings_count");
        
        
        return round(($ratings_sum/$ratings_count),2);
    }
    
    
    /**
    * CanRateGeodata
    * 
    * returns true, if current user can rate this geodataid
    * @param string $geodata_idorlink link or id of a geodata
    * @return bool state of can rate
    */
    function CanRateGeodata($geodata_id) {
        // Check cookies
        if($_COOKIE['rated'.$geodata_id] == 'true') {
            return false;
        } else {
            return true;
        }
    }
    
    
    /**
    * GetCommentsByGeodata
    * 
    * Get all comments sorted by creation to a geodata link/id
    * @param string $geodata_idorlink link or id of a geodata 
    * @return Array with data containg comment array
    */
    function GetCommentsByGeodata($geodata_idorlink) {
        if(strpos($geodata_idorlink,".")==false) {
            $geodata_idorlink=GeoLinkToId($geodata_idlink);
        } 
        return GetSelectAssocArray("SELECT * FROM comments WHERE geodata_id='".$geodata_idorlink."'");
    }
    
    /**
    * GetAllComments
    * 
    * Get all comments sorted by creation 
    * @return Array with data containg comment array
    */
    function GetAllComments() {
        return GetSelectAssocArray("SELECT * FROM comments ");
    }
    
    /**
    * GetAllFirstComments
    * 
    * Get all uniques comments sorted by creation 
    * @return Array with data containg comment array
    */
    function GetAllFirstComments() { 
        return GetSelectAssocArray("SELECT * FROM comments WHERE answer_to='' ORDER BY id DESC");
    }
    
   /**
    * GetCommentById
    * 
    * Get comment by id 
    * @param int $id Id of searched comment
    * @return Array with data containg comment array
    */
    function GetCommentById($id) {
        return GetSelectAssocArray("SELECT * FROM comments WHERE id=$id");
    }
    
    
    /**
    * GetCommentThread
    * 
    * Get a comment Thread (id comment and answers)
    * @param int $id Id of searched comment
    * @return Array with data containg comment array
    */
    function GetCommentThread($id) {
        return GetSelectAssocArray("SELECT * FROM comments WHERE id=$id OR answer_to=$id" );
    }
    
    
    /**
    * GetCommentsFiltered
    * 
    * Get all comments filtered by tag and/or keyword
    * @param string $tag [optional] tag which comment must contain
    * @param string $keyword [optional] keyword which comment must contain
    * @return Array with data containg comment array
    */
    function GetCommentsFiltered($tag, $keyword, $rate_min, $rate_max, $date_start, $date_end, $bbox,
                                                        $long, $lat, $distance) {
            $stack=null;

            // Generate where condition
            $wherecls="";

            // Search for tag
            if($tag!=null) {
                $tagid=TagsNameToId($tag);

                if($tagid!=null) {
                    if($wherecls!="") $wherecls.=" AND ";
                    $wherecls.="( TAGS_IDS LIKE '%$tagid%' )";
                }
            }

            // Search for keyword
            if($keyword!=null) {
                if($wherecls!="") $wherecls.=" AND ";
                $wherecls.="( TITLE LIKE '%$keyword%' OR CONTENT LIKE '%".$keyword."%' )";
            }

            
            // Search for date
            if($date_start!=null && $date_end!=null) {
                if($wherecls!="") $wherecls.=" AND ";
                $wherecls.="(timecreated >= STR_TO_DATE('$date_start', '%m/%d/%Y')
                AND timecreated <= STR_TO_DATE('$date_end', '%m/%d/%Y') + INTERVAL 1 DAY)";
            }
            
            
            // Search for boundingbox
            if($bbox!=null) {
                if($wherecls!="") $wherecls.=" AND ";
                $spli=explode(" ",$bbox);
                $wherecls.="positionx > '".$spli[0]. "' AND positiony > '".$spli[1].
                    "' AND positionx < '".$spli[2]. "' AND positiony < '".$spli[3]."'";
            }
            
            // Always only first ones
            if($wherecls!="") $wherecls.=" AND ";
            $wherecls.=" answer_to='' ";
            
            
            // Search for boundingbox
            if($long!=null && $lat!=null &&  $distance!=null  &&  $distance>0) {
                if($wherecls!="") $wherecls.=" AND ";
                // First calculate the long/lat distance, then convert to kilometres and compare to distance kilometres value
                $wherecls.="(ACOS(SIN(PI()*positionx/180.0)*SIN(PI()*$lat/180.0)+COS(PI()*positionx/180.0)"
                        . "*COS(PI()*$lat/180.0)*COS(PI()*$long/180.0-PI()*positiony/180.0))*6371) "
                        . "< ($distance / 10)";
            }
            
            
            // Create SQL Request
            $sql="SELECT * FROM comments WHERE $wherecls LIMIT 100";

            
            
            // Get and iterate through result
            $result=GetSelectResults($sql);
            if($result!=null) {
                while($row = $result->fetch_assoc()){
                    $valid=true;
                    
                    // Filter results after rating
                    if($rate_min!=null && $rate_max!=null) {
                        $avrating=GetAverageRating($row[id]);
                        if($avrating < $rate_min || $avrating > $rate_max) {
                            $valid=false;
                        }
                    }
                    
                    if($valid == true) {
                        if($stack==null) {
                                $stack = array($row);
                        } else {
                                array_push($stack, $row);
                        }
                    }
                    
                }
            }
            return $stack;
    }

 
    /**
    * GetUsernameFormatted
    * 
    * Get comment by id 
    * @param int $id Username
    * @return string html formatted username: cursive for anonym, link for users
    */
    function GetUsernameFormatted($id) {
        if($id==0 || $id>99999) return "<i>Anonym$id</i>";
        return "<a href='/index.php?user=$id'>" .
                GetUsername($id) . "</a>";
    }
    
    /**
    * GetUsername
    * 
    * Get username to id
    * @param int $id userid
    * @return string username
    */ 
    function GetUsername($id) {
        return GetSelectField("SELECT * FROM user WHERE id=$id","name");
    }
    
    /**
    * GetCommentIdByLink
    * 
    * Get comment by id 
    * @param int $id Username
    * @return string name of seachered User, null if nothing found
    */
    function GetCommentIdByLink($link) {
        return GetSelectField("SELECT * FROM comments WHERE permalink='$link'","id");
    }
    
    
    
    /**
    * RegisterUser
    * 
    * Register a new User and automaticly login
    * @param string $name username
    * @param string $mail mail adress
    * @param string $password password
    * @return int -1 = Name already exists, -2 = Mail Aready existis, x > 0 User ID
    */
    function RegisterUser($name, $mail, $password) {
        
        if($_SESSION["name"]=="") { // New Username
            // Check duplicates
            if(ExistsInDB("SELECT * FROM user WHERE name='$name'")) return -1;
            if(ExistsInDB("SELECT * FROM user WHERE mail='$mail'")) return -2;

            // Insert
            $usid=RequestInsert("INSERT INTO user (name, mail, password, registertime) VALUES ('$name', '$mail', '".MD5($password+$dbsalt)."', NOW())");
        } else { // Update infos, already got Username
            RequestUpdate("UPDATE user SET mail='$mail', password='".MD5($password+$dbsalt)."' WHERE name='$name';");
            $usid=GetSelectField("SELECT * FROM user WHERE name='$name'","id");
        }
        // AutoLogin
        Login($name, $password);
        
        return $usid;
    }

    /**
    * RegisterAnonymUser
    * 
    * Register a new anonym user, without datas and login
    * @param string $name username
    * @return int -1 = Name already exists, x > 0 User ID
    */
    function RegisterAnonymUser($name) {
        if(ExistsInDB("SELECT * FROM user WHERE name='$name'")) return -1;
         
        setcookie('name', $name, strtotime("+1 month"));
        $_SESSION["loggedin"] = "TRUE";
        $_SESSION["name"] = $name;
        $newid=RequestInsert("INSERT INTO user(name, isAnonym) VALUES ('$name',TRUE)");
        if($newid>0) $_SESSION["userid"]=$newid;
         
        echo $_SESSION["name"];
        return $newid;
    }
    
    /**
    * Login
    * 
    * Login a user with cookies/session 
    * @param string $name username    
    * @param string $password username
    * @return bool successfull login?
    */
    function Login($name, $password) {
        $resid=GetSelectField("SELECT * FROM user WHERE name='$name' && password='".MD5($password+$dbsalt)."'","id");
        if($resid=="") {
            return false;
        } else {
           
            $_SESSION["loggedin"] = "TRUE";
            $_SESSION["name"] = $name;
            $_SESSION["userid"] = $resid;
                 
            setcookie('name', $name, strtotime("+1 month"), "/", ".uni-muenster.de" );
            setcookie('password', MD5($password+$dbsalt), strtotime("+1 month"), "/", ".uni-muenster.de");
            $_COOKIE['name'] = $name; 
            $_COOKIE['password'] = $password; 

            return true;
        }
    }
    
    /**
    * Logout
    * 
    * Logout a user, delete cookies/session
    */
    function Logout() {
        $_SESSION["loggedin"] = "FALSE";
        $_SESSION["name"] = "";
        $_SESSION["userid"] = "";
        
        setcookie('name', "", strtotime("-1 month"), "/", ".uni-muenster.de" );
        setcookie('password', "", strtotime("-1 month"), "/", ".uni-muenster.de" );
        $_COOKIE['name'] = ""; 
        $_COOKIE['password']= "";
        
    }
    
    /**
    * IsLoggedIn
    * 
    * Is user logged in?
    * 
    * @return bool Loggedin?
    */
    function IsLoggedIn() {
        if($_COOKIE['name']!=null) {
            $resid=GetSelectField("SELECT * FROM user WHERE name='".$_COOKIE['name']."' && password='".$_COOKIE['password']."'","id");
            if($resid!="") {
		$_SESSION["userid"] = $resid;
                return true;
            }
        } 
        return false;
    }

    /**
    * GetMyUserid
    * 
    * Get userid for loggedin user
    * 
    * @return int userid
    */
    function GetMyUserid() {
        if(IsLoggedIn()) {
            return $_SESSION["userid"];
        }
    }
    
    
     /**
    * TagsIdsToName
    * 
    * Convert tagsid list ("13,11,33,..,") to tagsname list("tag1, tag2, ..,")
    * 
    * @param string $taglist tagsid list ("13,11,33,..,")
    * @param bool $nonew Create new tag entrys if name in list doesn't exists?
    * @return string tagsname list("tag1, tag2, ..,"), null if nothing found an $nonew==true
    */
    function TagsIdsToName($taglist) {
        $ret="";
        $spli = explode(",", $taglist);
        for($i=0; $i < count($spli); $i++) {
                $res=GetSelectField("SELECT * FROM tags WHERE id='".$spli[$i]."'","name");
                if($res=="") {	// No such tag exists
                       
                } else {
                      if(strlen ($ret)>0) $ret=", ".$ret;
                      $ret="<a href='index.php?tag=$res'>$res</a>".$ret;
                }
        }

        // Filter empty result
        if($ret=="") {
            return NULL;
        } else {
            return $ret;
        }
    }
    
    
    function TagsIdsToNameWithoutHtml($taglist) {
        $ret="";
        $spli = explode(",", $taglist);
        for($i=0; $i < count($spli); $i++) {
                $res=GetSelectField("SELECT * FROM tags WHERE id='".$spli[$i]."'","name");
                 
                if($res=="") {	// No such tag exists
                       
                } else {
                      if(strlen ($ret)>0) $ret=",".$ret;
                      $ret=$res.$ret; 
                }
        }

        // Filter empty result
        if($ret=="") {
            return NULL;
        } else {
            return $ret;
        }
    }
    
    	// TODO
    function UpdateAutoCompleteIndex($word) {
        $result=GetSelectResults("SELECT content, title FROM comments WHERE content LIKE '%$word%' OR title LIKE '%$word%'");
        $fullstr="";
        
        while($row = $result->fetch_assoc()){
            $fullstr.=$row["title"]." ";
            $fullstr.=$row["content"]." ";
            $fullstr.=". ";
        }
        
        $fullstr= str_replace(". ", " ", $fullstr);
        $fullstr= str_replace(", ", " ", $fullstr);
        $fullstr= str_replace(": ", " ", $fullstr);
        $fullstr= strtolower($fullstr);

        $spli = explode(" ", $fullstr);
        $stack = array("");
		
        for($i=0; $i < count($spli) ; $i++) {
                $cur=$spli[$i];
				
				if(in_array($cur,$stack)==false && strlen($cur)>2 && strlen($cur)<15 ) {
					for($d=0; $d < count($spli); $d++) {
						
						if(strcmp($cur,$spli[d]) == 0 && $d!=$i) {
							array_push($stack, $cur);
						}
					}
				}
        }
		
		
        return $fullstr;
    }
    
    /**
    * UpdateComment
    * 
    * Update title/content of a comment
    * 
    * @param string $id id of the comment
    * @param string $title new title
    * @return string $content new content
    */
    function UpdateComment($id, $title, $content) {
        // Security: Only changes when own comment
        $arr=GetCommentById($id);
        if($_SESSION["userid"]==$arr[0]["user_id"] ) {
            exit;
        }
        
        RequestUpdate("UPDATE comments SET title='$title', content='$content' WHERE id='$id'");
    }
    
    
    /*
     * GetUserDatas
     * 
     * Get different infos about a user
     * @param int id of the new user
     * @return string formatted date of the registrationdate
     */
    function GetUserRegistrationDate($id) {
        $date=GetSelectField("SELECT registertime FROM user WHERE id=" . $id, "registertime");
        $timestamp = strtotime($date);
        return date("m/d/Y", $timestamp);
    }
    
    
    /**
    * GetCommentByUser
    * 
    * Get comments written by a author
    * @param int $userid id of the author
    * @return Array with data containg comment array
    */
    function GetCommentsByUser($userid) {
        return GetSelectAssocArray("SELECT * FROM comments WHERE user_id='$userid' AND answer_to=''");
    }
    
    /**
     * GetTagsListed
     * 
     * Get a string with all tags, ordered by frequency 
     * 
     * @return string tag1, tag2,..
     */
    function GetTagsListed() {
        $str="";
        $tagarr=array();
        // Get all searched comments which have tags
        $arr=GetSelectAssocArray("SELECT * FROM comments WHERE tags_ids!=',4,' AND answer_to=''");
       
        
        for($i=0; $i<count($arr); $i++) {
            // Split Tag string
            $spli=explode(",", $arr[$i]["tags_ids"]);
            for($c=0; $c<count($spli); $c++) {
                // Sort into array
                if($spli[$c]!="") {
                      
                    // Search in array
                    $found=false;
                    for($f=0; $f<count($tagarr); $f++) {
                        if($tagarr[$f]["id"] == $spli[$c]) {
                            $found=true;
                            // +1 
                            $tagarr[$f]["count"]++;
                        }
                    }
                    
                    // Push new one into array
                    if($found==false) {
                        $sarr = array("id"=>$spli[$c],
                                "count"=>0);
                        array_push($tagarr, $sarr);
                    }
                    
                }
            }
        }

        // Sort created array
      /*  function cmp($a, $b)
        {
            return $b['count'] - $a['count'];
        }
        usort($tagarr, "cmp");
       */
        
        // Generate final str
        $str="";
        for($i=0; $i<count($tagarr); $i++) {
            $rword=TagsIdsToNameWithoutHtml( $tagarr[$i]["id"]);
            if(strlen(trim($rword))>2) {
                $str.= trim($rword);
                if($i<count($tagarr)-2) $str=$str.","; 
            } 
        }
        
       return $str;
    }
?>
