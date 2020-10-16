<?php

//This function will read in a file
function file_readContents()    {

    //Try to get a file handle
    try{
        $fileName = "data/MOCK_DATA.csv";
        $fileHandle = fopen($fileName,'r');
        if(!$fileHandle){
            throw new Exception("There was a problem opening the file $fileName!");
        }
        //Try to read in the file
        $contents = fread($fileHandle, filesize($fileName));
        if(empty($contents)){
            //If there was a problem throw an Exception
            throw new Exception("The files was empty!");
        }
    }
    catch (Exception $line) {
        echo $line->getMessage();
    }
    //Finally close the filehandle
    fclose($fileHandle);

    return $contents;
}
?>