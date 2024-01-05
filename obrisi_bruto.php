<?php
session_start();
include_once("baza.php");
$veza = veza_na_bazu();

if (isset($_GET["id"]) && $_GET["godina"] && $_GET["mjesec"]) {
    $zaposlenik = $_GET["id"];
    $mjesec = $_GET["mjesec"];
    $godina = $_GET["godina"];

    $neto_provjera = "SELECT COUNT(*) AS broji_neto FROM neto_placa 
                         WHERE zaposlenik = '{$zaposlenik}' AND mjesec = '{$mjesec}' AND godina = '{$godina}'";

    $neto_rezultat = $veza->query($neto_provjera);
    $neto_red = $neto_rezultat->fetch(PDO::FETCH_ASSOC);

    if ($neto_red['broji_neto'] == 0) {
        $upit = "DELETE FROM bruto_placa 
                 WHERE zaposlenik = '{$zaposlenik}' AND mjesec = '{$mjesec}' AND godina = '{$godina}'";

        $veza->query($upit);
    } else {
        header("Location: bruto.php?error=neto_postoji");
        exit();
    }

    header("Location: bruto.php");
}
?>
