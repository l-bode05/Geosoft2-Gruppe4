

 
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
    * @param string $content Comment content
    * @param string $title Comment Title
    * @param string $resources Comment resources
    * @param string $geodata_link Geodatalink for comment
    * @param string $user_id Id of user who created this comment
    * @param string $taglist taglist("tag1,tag2,..,") of comment
    * @param float $pointx, $pointy Position of comment
    */
    function InsertComment($content, $title, $resources, $geodata_link, $user_id, $taglist, $pointx, $pointy) {
      
        // Check for anonym comment
        if(strpos($user_id, "[anonym]")>-1) {
            echo "mm";
            $user_id= str_replace("[anonym]", "", $user_id);
            if(ExistsInDB("SELECT * FROM user WHERE name='$user_id'")==true) {
                return -1;
            }
            
            // Register Anonym user and convert to ID
            $user_id=RegisterAnonymUser($user_id);
        }
        
        // Convert TagNameString to TagIDString,
        $taglist=TagsNameToId($taglist);

        // Permalink from title
        $permalink=StrToPermalink($title, "comments");

        // Convert geodata_link to id
        $geodata_id=GeoLinkToId($geodata_link);

        // Finally insert new comment
        $sql = "INSERT INTO comments (content, title, resources, geodata_id, user_id, tags_ids, permalink, position) "
                . "VALUES ('".$content."','".$title."','".
                        $resources."','".$geodata_id."','".$user_id."','".$taglist."','".$permalink.
                            "', GeomFromText( 'POINT(".$pointx." ".$pointy.")' )   );";

        
        RequestInsert($sql);
    }

    /**
    * InsertCommentGeodata
    * 
    * Extension of 'InsertComment'. Add geodata values directly
    * @param datestamp $datestamp Date of geodata
    * @param ? $boundingbox Boundingbox of geodata
    */
    function InsertCommentGeodata($content, $title, $resources, $geodata_link, $user_id, $taglist, $pointx, $pointy, $datestamp, $boundingbox) {
            InsertComment($content, $title, $resources, $geodata_link, $user_id, $taglist,$pointx, $pointy);
            SetGeodataDetails($link,$datestamp,$boundingbox);
    }

    /**
    * GetComments
    * 
    * Get all comments sorted by creation to a geodata link
    * @param string $geodata_link link of a geodata 
    * @return Array with data containg comment array
    */
    function GetComments($geodata_link) {
            $geodata_id=GeoLinkToId($geodata_link);
            $stack=null;
            $result=GetSelectResults("SELECT * FROM comments WHERE geodata_id='".$geodata_id."'");

            while($row = $result->fetch_assoc()){
                    if($stack==null) {
                            $stack = array($row);
                    } else {
                            array_push($stack, $row);
                    }

            }

            return $stack;
    }

    /**
    * GetCommentsFiltered
    * 
    * Get all comments filtered by tag and/or keyword
    * @param string $tag [optional] tag which comment must contain
    * @param string $keyword [optional] keyword which comment must contain
    * @return Array with data containg comment array
    */
    function GetCommentsFiltered($tag, $keyword) {

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

            // Create SQL Request
            $sql="SELECT * FROM comments WHERE $wherecls LIMIT 100";

            // Get and iterate through result
            $stack=null;
            $result=GetSelectResults($sql);
            while($row = $result->fetch_assoc()){
                if($stack==null) {
                        $stack = array($row);
                } else {
                        array_push($stack, $row);
                }
            }

            return $stack;
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
        // Check duplicates
        if(ExistsInDB("SELECT * FROM user WHERE name='$name'")) return -1;
        if(ExistsInDB("SELECT * FROM user WHERE mail='$mail'")) return -2;

        // Insert
        $usid=RequestInsert("INSERT INTO user (name, mail, password, registertime) VALUES ('$name', '$mail', '".MD5($password+$dbsalt)."', NOW())");
        
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
        $_SESSION["userid"]=RequestInsert("INSERT INTO user(name, isAnonym) VALUES ('$name',TRUE)");
        
        return $_SESSION["userid"];
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
                 
            setcookie('name', $name, strtotime("+1 month"));
            setcookie('password', MD5($password+$dbsalt), strtotime("+1 month"));
            $_COOKIE['name'] = $name; // fake-cookie setzen
            $_COOKIE['password'] = $password; // fake-cookie setzen

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
        
        setcookie('name', "", strtotime("-1 month"));
        setcookie('password', "", strtotime("-1 month"));

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

    
    
    
    	// TODO
    function UpdateAutoCompleteIndex($word) {
        $result=getselectresults("SELECT content, title FROM comments WHERE content LIKE '%$word%' OR title LIKE '%$word%'");
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
?>
