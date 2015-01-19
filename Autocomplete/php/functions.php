<?php

require_once('config.php');

/**
 * 
 * @param type $query
 */
function autoSuggest($query)
{
 
    /**
     * Connection to database and request of database values $sql
     */
    $connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    mysql_select_db(DB_NAME, $connect);
    
    $sql = '
    SELECT content, title
    FROM comments
    WHERE content LIKE "'.mysql_real_escape_string($query).'%"
    OR title LIKE "'.mysql_real_escape_string($query).'%"
     ';
    
    $result = mysql_query($sql, $connect);
    $totalRows = mysql_num_rows($result);
    
    /**
     * if-request:  if there are entries (at least one) in the requested list of database values
     *              a row of entries is given, else return the error "No results found."
     *              $itmes are the elements of the autosuggest-container
     */
    
    if($totalRows > 0)
    {

    $itmes = '<ul class="autosuggest">';
    
    while ($row = mysql_fetch_assoc($result))
    {
        $items .= '<li>'.$row['content'].' - '.$row['title'].'</li>';
    }

    $items .= '</ul>';
    
    }
 else {
     
    $items = 'No results found.';
    
   }
    echo $items;
    }

?>

