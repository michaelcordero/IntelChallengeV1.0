<?php
/**
 * Created by PhpStorm.
 * User: michaelcordero
 * Date: 6/25/16
 * Time: 7:53 AM
 */


function clearData(){
    try{
        $link = mysqli_connect("localhost","root","Qqq#1080","wordpress");
        mysqli_query($link,'TRUNCATE TABLE IntelChallenge');
        echo "Data succesfully cleared.";
    }catch (mysqli_sql_exception $e ){
        echo "<h2>" . $e->getMessage() . "</h2>";
    }
}

function clearButton(){
    
}

?>