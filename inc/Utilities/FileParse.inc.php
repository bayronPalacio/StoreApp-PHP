<?php

//This function parses the contents of a file.
function parseEvents($eventsFileContents)   {

    //Store the events
    $eventsArr = array();

    //Split by \n
    $row = explode("\n",$eventsFileContents);
    
    // var_dump($row);
    //go through each line
    for($r = 1; $r < (count($row)-1); $r++)
    {
        try{
            //Split by ','
            $cols = explode(",",$row[$r]);
            // echo count($cols);
            if(count($cols) != 14){
                throw new Exception("There was a problem parsing the file on line ".($r+1)."<BR>");
            }

        }
        catch (Exception $line){
            echo $line -> getMessage();
        }

        for($c = 0; $c < count($cols); $c++)
        {
            //Build a 2d array of the file.
            $eventsArr[$r][$c] = $cols[$c];
        }
    }       
    
    return $eventsArr;
}
?>