<?php
session_start();
include_once("baza.php");
$veza = veza_na_bazu();
?>

<!DOCTYPE html>
<html lang=hr>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Daria Komić">
        <link href="stil.css" rel="stylesheet" type="text/css">
        <?php 
        include_once("nav.php");
        ?>
        <body>
        <?php
        if(isset($_SESSION["korime"])){ 
            echo "<div  style='float:right;'>Pozdrav, " . $_SESSION["ime"] . "! </div>"; }
        ?>
        <title>Evidentiraj radni dan</title>
    </head>
    <body>
        <?php
        if(isset($_POST["submit"])){
            try{
                $zaposlenik = $_POST["zaposlenik"];
                $datum = $_POST["datum"];
                $pocetak = $_POST["vrijeme_pocetka"];
                $zavrsetak = $_POST["vrijeme_zavrsetka"];
                $zastoj = $_POST["sati_zastoja"];
                $terenski_rad = $_POST["sati_terenskog_rada"];
                $pripravnost = $_POST["sati_pripravnosti"];
                $nenazocnost = $_POST["sati_nenazocnosti"];
                if(isset($_POST["razlog"]) && $_POST["razlog"] == "0"){
                    $razlog = null;
                }else{
                    $razlog = $_POST["razlog"];
                }
                $ukupno = $_POST["ukupno"];
            
                $upit_unos = "INSERT INTO evidencija_rada_zaposlenika 
                (zaposlenik, datum, vrijeme_pocetka_rada, vrijeme_zavrsetka_rada, sati_zastoja_u_radu, sati_terenskog_rada, sati_pripravnosti, vrijeme_nenazocnosti_u_radu, razlog_nenazocnosti_u_radu, ukupno_dnevno_vrijeme_rada)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $rezultat = $veza->prepare($upit_unos);

                $rezultat->bindParam(1, $zaposlenik);
                $rezultat->bindParam(2, $datum);
                $rezultat->bindParam(3, $pocetak);
                $rezultat->bindParam(4, $zavrsetak);
                $rezultat->bindParam(5, $zastoj);
                $rezultat->bindParam(6, $terenski_rad);
                $rezultat->bindParam(7, $pripravnost);
                $rezultat->bindParam(8, $nenazocnost);
                $rezultat->bindParam(9, $razlog, PDO::PARAM_NULL);
                $rezultat->bindParam(10, $ukupno);

                $rezultat->execute();

                echo "<br> Evidencija za " . $datum . " uspješno kreirana.";
            }
            catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        <form method="post" action="evidencija_rada.php">
        <div>
            <h1>Evidentiraj radni dan</h1>
            <label for="zaposlenik">Zaposlenik</label>
            <br>
            <select id="zaposlenik" name="zaposlenik" required>            
              <?php 
             
                $upit = "SELECT * FROM zaposlenik";
                $rezultat = $veza->query($upit);
                $redovi = $rezultat->fetchAll();

                if ($redovi) {
                    foreach ($redovi as $red) {
                        ?>
                        <option value="<?= $red['oib']; ?>">
                            <?= $red['ime'] . ' ' . $red['prezime']; ?>
                        </option>
                        <?php
                    }
                } else {
                    echo "Ne postoji niti jedan zaposlenik u bazi!";
                }
                ?>
            </select>
        </div>
        <br>
        <div>
            <label for="datum">Datum</label>
            <br>
            <input type="text" id="datum" name="datum" placeholder="Unesite datum u formatu: gggg-mm-dd" required/>
        </div>
        <br>
        <div>
            <label for="vrijeme_pocetka">Vrijeme početka rada</label>
            <br>
            <input type="text" id="vrijeme_pocetka" name="vrijeme_pocetka" placeholder="Unesite vrijeme početka radnog dana u formatu 00:00:00" required/>
        </div>
        <br>
        <div>
            <label for="vrijeme_zavrsetka">Vrijeme završetka rada</label>
            <br>
            <input type="text" id="vrijeme_zavrsetka" name="vrijeme_zavrsetka" placeholder="Unesite vrijeme završetka radnog dana u formatu 00:00:00" required/>
        </div>
        <br>
        <div>
            <label for="sati_zastoja">Vrijeme zastoja u radu</label>
            <br>
            <input type="text" id="sati_zastoja" name="sati_zastoja" placeholder="Unesite sate zastoja u radu" required/>
        </div>
        <br>
        <div>
            <label for="sati_terenskog_rada">Sati terenskog rada</label>
            <br>
            <input type="text" id="sati_terenskog_rada" name="sati_terenskog_rada" placeholder="Unesite sate terenskog rada" required/>
        </div>
        <br>
        <div>
            <label for="sati_pripravnosti">Sati pripravnosti</label>
            <br>
            <input type="text" id="sati_pripravnosti" name="sati_pripravnosti" placeholder="Unesite sate pripravnosti" required/>
        </div>
        <br>
        <div>
            <label for="sati_nenazocnosti">Sati nenazočnosti u radu</label>
            <br>
            <input type="text" id="sati_nenazocnosti" name="sati_nenazocnosti" placeholder="Unesite sate nenazočnosti na radu" required/>
        </div>
        <br>
        <div>
            <label for="razlog">Razlog nenazočnosti</label>
            <br>
            <select id="razlog" name="razlog" required> 
            <option value="0">Bez razloga</option>           
              <?php 
                 
                $upit_razlog = "SELECT * FROM razlog_nenazocnosti ";
                $rezultat_razlog = $veza->query($upit_razlog);
                $redovi_razlog = $rezultat_razlog->fetchAll();

                if ($redovi_razlog) {
                    foreach ($redovi_razlog as $red_razlog) {
                        ?>
                        <option value="<?= $red_razlog['id_razloga']; ?>">
                            <?= $red_razlog['naziv']; ?>
                        </option>
                        <?php
                    }
                } else {
                    echo "Ne postoji niti jedan razlog nenazočnosti u bazi!";
                }
                ?>
            </select>
        </div>
        <br>  
        <div>
            <label for="ukupno">Ukupni sati rada</label>
            <br>
            <input type="text" id="ukupno" name="ukupno" placeholder="Unesite ukupne sate rada" required/>
        </div>
        <br>  
        <div>
            <input type="submit" value="Unesi" name="submit" id="submit" />
        </div>            
    </form>
    </body>
</html>





