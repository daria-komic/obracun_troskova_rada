<?php 
    session_start();
    include_once("baza.php");
    $veza = veza_na_bazu();
    
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aplikacija za obračun troškova rada</title>
		<meta name="autor" content="Komic Daria"/>
		<meta name="datum" content="30.12.2023."/>
		<meta charset="utf-8"/>
        <link href="stil.css" rel="stylesheet" type="text/css">
	</head>
    <?php
        include_once("nav.php");
    ?>
    <body>
    <?php
    if(isset($_SESSION["korime"])){ 
        echo "<div  style='float:right;'>Pozdrav, " . $_SESSION["ime"] . "! </div>"; }
    ?>
    <h1>Aplikacija za obračun troškova rada</h1>
    <br>
    </body>
</html>