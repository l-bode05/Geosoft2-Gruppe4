

<?php
/**
 * Project specific help functions, to handle 
 * the access easier
 * 
 * 
 */

    include "dbbasicfunctions.php";
    
    
    /**
    * GeoDataLinkExists
    * 
    * Checks if a Link already exists in the geodatabase
    * @param string $link link to search for
    * @return bool link found?
    */
    function GeoDataLinkExists($link) {
            if(ExistsInDB("SELECT * FROM geodata WHERE url=".$link)) return true;
            return false;
    }

    /**
    * SetGeodataDetails
    * 
    * Update datas to an existing Geodata entry
    * @param string $link link identifier of GeoData
    * @param string $datestamp new timestamp
    * @param string $boundingbox new boundingbox
    */
    function SetGeodataDetails($link, $datestamp, $boundingbox) {
            $sql="UPDATE geodata SET datestamp = ".$datestamp.", boundingbox=".$boundingbox." ";
            RequestUpdate($sql);
    }

    /**
    * GeoLinkToId
    * 
    * Get the ID of the selected geodatalink, insert a new one when 
    * there isn't a geodata entry with this link 
    * @param string $link link identifier of GeoData
    * @return int ID 
    */
    function GeoLinkToId($link) {
            $res=GetSelectField("SELECT * FROM geodata WHERE url='".$link."'","id"); 
            if($res=="") { // Create Entry
                    $sql="INSERT INTO geodata(url) VALUES('".$link."');";
                    return RequestInsert($sql);
            } else {
                    return $res;
            }
    }

    
    /**
    * InsertTag
    * 
    * Insert a new tag
    * @param string $name name of new tag
    * @return int ID of inserted tag 
    */
    function InsertTag($name) {
            $permalink=StrToPermalink($name, "tags");
            $sql="INSERT INTO tags(name, permalink) VALUES('".$name."','".$permalink."');";
            return RequestInsert($sql);
    }


    /**
    * StrToPermalink
    * 
    * Convert an string to url conform format
    * for Tags/Comments.
    * If permalinks already existis, add a -n suffix
    * 
    * @param string $str string
    * @param string $database database to search for existing permalinks (tag/comment)
    * @return int ID of inserted tag 
    */
    function StrToPermalink($str, $database) {
            $str=strtolower($str);
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', '', $str);
            // Check if it already exists
            $num=0;
            $curperma=$str;
            while(ExistsInDB("SELECT * FROM ".$database." WHERE permalink=".$curperma)==true) {
                    $num++;
                    $curperma=$str."-".$num;
            }

            return $curperma;
    }


    /**
    * tagsnametoid
    * 
    * Convert tagsname list("tag1,tag2,..,") to tagsid list ("13,11,33,..,")
    * 
    * @param string $taglist tagsname list("tag1,tag2,..,")
    * @param bool $nonew Create new tag entrys if name in list doesn't exists?
    * @return tagsid list ("13,11,33,..,"), null if nothing found an $nonew==true
    */
    function TagsNameToId($taglist, $nonew=FALSE) {
        $ret="";
        $spli = explode(",", $taglist);

        for($i=0; $i < count($spli); $i++) {
                $res=GetSelectField("SELECT * FROM tags WHERE name='".$spli[$i]."'","id");

                if($res=="") {	// No such tag exists
                        if($nonew==FALSE) $ret.=",".InsertTag($spli[$i]);	// Create new Tag
                } else {
                        $ret.=",".$res;
                }
        }

        // Filter empty result
        if($ret=="") {
            return NULL;
        } else {
            return $ret.",";
        }
    }

?>