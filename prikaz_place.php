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
        <h1>Prikaz plaće po zaposleniku</h1>
        <br>
            <form method="get" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <label for="zaposlenik">Zaposlenik</label>
                <br>
                <select id="zaposlenik" name="zaposlenik" required> 
                    <option value="">Odaberi zaposlenika</option> 
                <?php 
                
                    $upit = "SELECT * FROM zaposlenik";
                    $rezultat = $veza->query($upit);
                    $redovi = $rezultat->fetchAll();

                    if ($redovi) {
                        foreach ($redovi as $red) {
                            $selected = (isset($_GET['zaposlenik']) && $_GET['zaposlenik'] === $red['oib']) ? 'selected' : '';
                    
                            ?>
                            <option value="<?= $red['oib']; ?>" <?= $selected; ?>>
                                <?= $red['ime'] . ' ' . $red['prezime']; ?>
                            </option>
                            <?php
                        }
                    } else {
                        echo "Ne postoji niti jedan zaposlenik u bazi!";
                    }
                    ?>
                </select>
                <br>
                <br>
                <label for="mjesec">Mjesec</label>
                <br>
                <input type="text" placeholder="Upišite mjesec ovdje" id="mjesec" name="mjesec" required value="<?= isset($_GET['mjesec']) ? $_GET['mjesec'] : ''; ?>"/>
                <br>
                <br>
                <label for="mjesec">Godina</label>
                <br>
                <input type="text" placeholder="Upišite godinu ovdje" id="godina" name="godina" required value="<?= isset($_GET['godina']) ? $_GET['godina'] : ''; ?>">
                <br>
                <br>
                <br>
                <input name="submit" type="submit" value="Prikaži obračunsku listu"/>
                
            </form>
            <br>

                 
                <?php
                if(isset($_GET["zaposlenik"]) && isset($_GET["mjesec"]) && isset($_GET["godina"])){
                    $id = $_GET["zaposlenik"];
                    $mjesec = $_GET["mjesec"];
                    $godina = $_GET["godina"];
                        
                    $upit = "SELECT 
                    d.zaposlenik,
                    z.ime,
                    z.prezime,
                    d.mjesec,
                    d.godina,
                    b.iznos AS bruto,
                    d.doprinos_za_mirovinsko,
                    d.osobni_odbitak,
                    d.porezna_osnovica,
                    d.iznos_poreza,
                    d.iznos_prireza,
                    n.iznos AS neto,
                    d.iznos_doprinosa,
                    d.trosak_za_poslodavca
                    FROM dijelovi_obracuna_place d
                    LEFT JOIN zaposlenik z ON d.zaposlenik = z.oib
                    LEFT JOIN bruto_placa b ON b.zaposlenik = d.zaposlenik
                    LEFT JOIN neto_placa n ON n.zaposlenik = d.zaposlenik
                    WHERE d.zaposlenik = '{$id}' AND d.mjesec = '{$mjesec}' AND d.godina = '{$godina}'";

                    $rezultat = $veza->query($upit);

                    if ($rezultat->rowCount() > 0) {
                        while ($red = $rezultat->fetch()) {
                            $dohodak = $red["bruto"] - $red["doprinos_za_mirovinsko"];
                ?>
                <h2>Obračunska lista</h2>      
                <div>
                    <div style="font-weight:bold; font-size: 20px ">
                        <?php echo "{$red['ime']} {$red['prezime']}"; ?>
                    </div>
                    <div style="font-weight:bold; font-size: 20px" >
                        <?php echo "{$red['mjesec']}"; ?> / <?php echo "{$red['godina']}"; ?>
                    </div>
                </div>
                <br>
                <br>
                <table style="width:50%; margin-left: 25%; margin-right: 25%">
                            <tbody>
                                
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">1. Bruto plaća:</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['bruto']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">2. Doprinosi za mirovinsko I i II. stup (20%):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['doprinos_za_mirovinsko']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">3. Dohodak (1-2):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $dohodak; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">4. Osobni odbitak zaposlenika UKUPNO:</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['osobni_odbitak']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">5. Osnovica za porez na dohodak (3-4):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['porezna_osnovica']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">6. Porez na dohodak (UKUPNO):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['iznos_poreza']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">7. Prirez:</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['iznos_prireza']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">8. Neto plaća (3-7):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['neto']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">9. Doprinosi na plaću (15,5%):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['iznos_doprinosa']; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:bold" colspan="3">10. Trošak za poslodavca (1+9):</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; width:30%" colspan="3"><?php echo $red['trosak_za_poslodavca']; ?></td>
                                </tr>
                            </tbody>
                        
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='2'>Nema rezultata za odabranog zaposlenika.</td></tr>";
                    }
                }
               
                ?>
         </table>
    </body>
</html>