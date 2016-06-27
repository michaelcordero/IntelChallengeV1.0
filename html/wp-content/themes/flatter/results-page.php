<!--Wordpress template for displaying all pages.-->
<!--@link https://codex.wordpress.org/Template_Hierarchy-->
<!--@package Flatter-->
<!--Created by PhpStorm.-->
<!--User: michaelcordero-->
<!--Date: 6/23/16-->
<!--Time: 12:49 PM-->

<?php get_header(); ?>

<section class="page-header" style="background:#404040 url(<?php if (get_header_image()) {header_image();} ?>">
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

//Global Variables
global $wpdb;  //wordpress global variable that interacts with Database

//PHP Data Object:: Optimized PHP object for interacting with Database
$pdo = new PDO("mysql:host=localhost;dbname=wordpress", 'root', 'Qqq#1080',
        array(
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_READ_DEFAULT_GROUP => 'client') //PHP v5.5 bug workaround
    );

// Clear Data Functionality

$existing_data = $wpdb->get_results("SELECT * FROM wordpress.IntelChallenge", ARRAY_N);

if (isset($_POST["Clear"]) && !empty($existing_data)) {
        $pdo->query('TRUNCATE TABLE IntelChallenge');
        echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=' . $current_URL . '">';
}

//Create Data Table

if (!empty($existing_data)) {
    echo "<h1>" . "Data Table" . "</h1>";
    echo "<table border='1' cellspacing='5em' cellpadding='5'>";
    foreach ($existing_data as $row) {
        echo "<tr>" . "<td>" . $row[0] . "</td>" . "<td>" . $row[1] . "</td>" . "<td>" . $row[2] . "</td>" . "<td>" . $row[3] . "</td>" . "<td>" . $row[4] . "</td>" . "</tr>";
    }
    echo "</table>";
    echo "<br/>" . "<br/>";

//Create Data Set
    $trojan = $wpdb->get_var("SELECT COUNT(*)  FROM `IntelChallenge` WHERE `ClassificationType` LIKE 'trojan'");
    $clean = $wpdb->get_var("SELECT COUNT(*)  FROM `IntelChallenge` WHERE `ClassificationType` LIKE 'clean'");
    $virus = $wpdb->get_var("SELECT COUNT(*)  FROM `IntelChallenge` WHERE `ClassificationType` LIKE 'virus'");
    $unknown = $wpdb->get_var("SELECT COUNT(*)  FROM `IntelChallenge` WHERE `ClassificationType` LIKE 'unknown'");
    $pup = $wpdb->get_var("SELECT COUNT(*)  FROM `IntelChallenge` WHERE `ClassificationType` LIKE 'pup'");

    echo "<h1>" . "Data Detection Results" . "</h1>";
    echo "<table border='1' cellspacing='5em' cellpadding='5'>";
    echo "<tr>" . "<td>" . "Trojan Files: " . "</td>" . "<td>" . $trojan . "</td>" . "<tr/>";
    echo "<tr>" . "<td>" . "Clean Files: " . "</td>" . "<td>" . $clean . "</td>" . "<tr/>";
    echo "<tr>" . "<td>" . "Virus Files: " . "</td>" . "<td>" . $virus . "</td>" . "<tr/>";
    echo "<tr>" . "<td>" . "Unknown Files: " . "</td>" . "<td>" . $unknown . "</td>" . "<tr/>";
    echo "<tr>" . "<td>" . "Pup Files: " . "</td>" . "<td>" . $pup . "</td>" . "<tr/>";
    echo "</table>";
    echo "<br/>" . "<br/>";
} else {
    for ($i = 0; $i < 10; $i++) {
        echo "<br/>";
    }
}

if ($_FILES[csv][size] > 0) {

//get the csv file
    $csvfile = $_FILES[csv][tmp_name];
    $file_type = mime_content_type($csvfile);

//  Verify file format; Wordpress method validate_file() protects against file directory traversal attack and invalid file types.
    $mimes = array("application/vnd.ms-excel", "text/plain", "text/csv", "text/tsv");

    if (validate_file($file_type, $mimes) != 0) {
        echo("<h3>" . "Sorry " . $file_type . ", not allowed. Please click Intel tab on menu to refresh page" . "</h3>" . "<br/>");
        get_Footer();
        die;
    }

//  Execute Insert Query
    try {
        $pdo->exec("
      LOAD DATA LOCAL INFILE " . $pdo->quote($csvfile) . " INTO TABLE `IntelChallenge`
      FIELDS TERMINATED BY " . $pdo->quote(",") . "
      LINES TERMINATED BY " . $pdo->quote("\n"));
        echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=' . $current_URL . '">';
    } catch (PDOException $e) {
        echo "<h2>" . $e->getMessage() . "</h2>";
    }
} ?>

<!--Form to take user input of CSV-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <h1>Choose your file: </h1> <br/>
    <div><input name="csv" type="file" id="csv" accept=".csv"/></div>
    <br/>
    <input type="submit" name="Submit" value="Submit"/> &nbsp;&nbsp;
    <input type="submit" name="Clear" value="Clear Data"/>
</form>
</body>
</html>
<?php get_footer();
