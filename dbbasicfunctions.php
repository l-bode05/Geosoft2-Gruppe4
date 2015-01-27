

<?php
/**
 * Basic functions to run get and edit SQL cmds
 */

    /**
    * RequestInsert
    * 
    * Run a Insert statement
    * @param string $sql Statement to run
    * @return int ID of inserted row
    */
    function RequestInsert($sql)
    {
        
                
        if ($GLOBALS["conn"]->query($sql) === false) {
                echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
        }

        return  $GLOBALS["conn"]->insert_id;
    }


    /**
    * RequestUpdate
    * 
    * Run a update statement
    * @param string $sql Statement to run
    * @return int ID of affected rows
    */
    function RequestUpdate($sql)
    {
        if ($GLOBALS["conn"]->query($sql) === false) {
                echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
        }

        return  mysqli_affected_rows($GLOBALS["conn"]);
    }

    /**
    * GetSelectResults
    * 
    * Run a select statement
    * @param string $sql Statement to run
    * @return array[] Selected datas
    */
    function GetSelectResults($sql) {
        $result=$GLOBALS["conn"]->query($sql);

        return $result;
    }

    /**
    * GetSelectField
    * 
    * Run a select statement and get the field data of 
    * the first result
    * @param string $sql Statement to run
    * @param string $field Name of the wanted field
    * @return string Value of the first result, "" if nothing found
    */
    function GetSelectField($sql, $field) {
        $result=$GLOBALS["conn"]->query($sql);

        while($row = $result->fetch_assoc()){
                if($row[$field]!="") return $row[$field];
        }
        return "";
    }

    /**
    * ExistsInDB
    * 
    * Run a select statement and check if it finds sth
    * @param string $sql Statement to run
    * @return bool found a result with this statement?
    */
    function ExistsInDB($sql) {
        $result=$GLOBALS["conn"]->query($sql);
        if($result->num_rows < 1) return false;
        return true;	
    }

    /**
     * requireToVar
     * 
     * Includes an other file 
     * @param $file path
     * @return string content
     * 
     */
    function requireToVar($file){
        ob_start();
        require($file);
        return ob_get_clean();
    }
?>
