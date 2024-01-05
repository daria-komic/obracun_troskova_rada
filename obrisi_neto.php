<?php

session_start();
include_once("baza.php");
$veza = veza_na_bazu();

if (isset($_GET["id"]) && $_GET["godina"] && $_GET["mjesec"]) {
    $zaposlenik = $_GET["id"];
    $mjesec = $_GET["mjesec"];
    $godina = $_GET["godina"];

    $upit = "DELETE FROM neto_placa 
             WHERE zaposlenik = '{$zaposlenik}' AND mjesec = '{$mjesec}' AND godina = '{$godina}'";

    $veza->query($upit);

    header("Location: neto.php");
}
?>
