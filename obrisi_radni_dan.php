<?php
session_start();
include_once("baza.php");
$veza = veza_na_bazu();

if (isset($_GET["id"]) && $_GET["datum"]) {
    $zaposlenik = $_GET["id"];
    $datum = $_GET["datum"];

    $upit = "DELETE FROM evidencija_rada_zaposlenika 
             WHERE zaposlenik = '{$zaposlenik}' AND datum = '{$datum}'";

    $veza->query($upit);

   header("Location: prikaz_evidencije.php");
}
?>
