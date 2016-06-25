<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Flatter
 */
/**
 * Created by PhpStorm.
 * User: michaelcordero
 * Date: 6/23/16
 * Time: 12:49 AM
 */
/*
Template Name: Results
*/

get_header(); ?>

	<section class="page-header" style="background:#404040 url(<?php if ( get_header_image() ) { header_image(); }  ?>)">
    <div class="container">
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="block">
                    <h1 class="page-title"><?php the_title(); ?></h1>
	                    <div class="underline"></div>
<?php flatter_breadcrumbs(); ?>
                </div>
            </div>
	        </div>
	    </div>
	</section>
<br/>

<?php
global $wpdb;  //wordpress global variable that interacts with DB
$clearButton;
$results = $wpdb->get_results("SELECT * FROM wordpress.IntelChallenge", ARRAY_N);
if(!empty($results)){
    echo "<h1>"."Data Table". "</h1>";
    echo "<table border='1' cellspacing='5em' cellpadding='5'>";
   foreach($results as $row){
       echo "<tr>"."<td>".$row[0]."</td>"."<td>".$row[1]."</td>"."<td>".$row[2]."</td>"."<td>".$row[3]."</td>"."<td>".$row[4]."</td>"."</tr>";
   }
    echo "</table>";
   $clearButton=true;
}else{
    echo "<br/>"."<br/>"."<br/>"."<br/>"."<br/>"."<br/>"."<br/>"."<br/>"."<br/>"."<br/>";
    $clearButton=false;
}

function clearData(){
    try{
        $link = mysqli_connect("localhost","root","Qqq#1080","wordpress");
        mysqli_query($link,'TRUNCATE TABLE IntelChallenge');
    }catch (mysqli_sql_exception $e ){
        echo "<h2>" . $e->getMessage() . "</h2>";
    }


}
?>

<?php

$databasehost = "localhost";
$databasename = "wordpress";
$databasetable = "IntelChallenge";
$databaseusername="root";
$databasepassword = "Qqq#1080";
$fieldseparator = ",";
$lineseparator = "\n";

if ($_FILES[csv][size] > 0) {

//get the csv file
$csvfile = $_FILES[csv][tmp_name];

    try {
        $pdo = new PDO("mysql:host=$databasehost;dbname=$databasename",
            $databaseusername, $databasepassword,
            array(
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_READ_DEFAULT_GROUP => 'client')
        );
    } catch (PDOException $e) {
        die("database connection failed: " . $e->getMessage());
        echo "<h2>" . $e->getMessage() . "</h2>";
    }
    try {
        $affectedRows = $pdo->exec("
      LOAD DATA LOCAL INFILE " . $pdo->quote($csvfile) . " INTO TABLE `$databasetable` 
      FIELDS TERMINATED BY " . $pdo->quote($fieldseparator) . "
      LINES TERMINATED BY " . $pdo->quote($lineseparator));
        echo "Success! Loaded $affectedRows total records from the csv file.\n";
        header('Location: '.$_SERVER['REQUEST_URI']);
    } catch (PDOException $e) {
        echo "<h2>" . $e->getMessage() . "</h2>";
    }

} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    Choose your file: <br />
    <input name="csv" type="file" id="csv" accept=".csv" />
    <input type="submit" name="Submit" value="Submit" disabled=""/>
    <input type="submit" name="Clear" class="button" value="Clear Data" disabled=""  />
</form>
</body>
</html>

<?php get_footer(); ?>