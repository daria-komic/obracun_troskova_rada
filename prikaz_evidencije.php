<?php 
    session_start();
    include_once("baza.php");
    $veza = veza_na_bazu();
    
    if(isset($_GET["id"]) && $_GET["datum"]){
        $zaposlenik = $_GET["id"];
        $datum = $_GET["datum"];
    }
    
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
    <script>
        function confirmDelete(zaposlenik, datum) {
        var result = confirm("Jeste li sigurni da želite obrisati ovaj redak?");

            if (result) {
                window.location.href = `obrisi_radni_dan.php?id=${zaposlenik}&datum=${datum}`; 
            }
        }

    </script>
    <body>
        <?php
        if(isset($_SESSION["korime"])){ 
            echo "<div  style='float:right;'>Pozdrav, " . $_SESSION["ime"] . "! </div>"; }
        ?>
        <h1>Prikaz evidencije po zaposlenicima</h1>
        <br>
            <form method="get" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <label for="zaposlenik">Zaposlenik</label>
                <br>
                <select id="zaposlenik" name="zaposlenik" required>  
                <option value="0" <?php if (!isset($_GET['zaposlenik']) || (isset($_GET['zaposlenik']) && $_GET['zaposlenik'] === '0')) echo 'selected'; ?>>Svi zaposlenici</option>          
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
                <input type="text" placeholder="Upišite mjesec ovdje" id="mjesec" name="mjesec" value="<?= isset($_GET['mjesec']) ? $_GET['mjesec'] : ''; ?>"/>
                <br>
                <br>
                <label for="mjesec">Godina</label>
                <br>
                <input type="text" placeholder="Upišite godinu ovdje" id="godina" name="godina" value="<?= isset($_GET['godina']) ? $_GET['godina'] : ''; ?>">
                <br>
                <br>
                <input name="submit" type="submit" value="Filtriraj"/>
                
            </form>
            <br>
        <table>
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Datum</th>
                    <th>Vrijeme početka rada</th>
                    <th>Vrijeme završetka rada</th>
                    <th>Sati zastoja u radu</th>
                    <th>Sati terenskog rada</th>
                    <th>Sati pripravnosti</th>
                    <th>Vrijeme nenazočnosti na radu</th>
                    <th>Razlog nenazočnosti</th>
                    <th>Ukupno dnevno radno vrijeme</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php   
                    $upit = "SELECT 
                    e.zaposlenik,
                    z.ime,
                    z.prezime,
                    e.datum,
                    e.vrijeme_pocetka_rada,
                    e.vrijeme_zavrsetka_rada,
                    e.sati_zastoja_u_radu,
                    e.sati_terenskog_rada,
                    e.sati_pripravnosti,
                    e.vrijeme_nenazocnosti_u_radu,
                    r.naziv AS razlog,
                    e.ukupno_dnevno_vrijeme_rada
                    FROM evidencija_rada_zaposlenika e
                    LEFT JOIN zaposlenik z ON e.zaposlenik = z.oib
                    LEFT JOIN razlog_nenazocnosti r ON e.razlog_nenazocnosti_u_radu = r.id_razloga";

                    if (isset($_GET["zaposlenik"])) {
                        $id = $_GET["zaposlenik"];

                        if ($id !== '0') {
                            $upit .= " WHERE e.zaposlenik = '{$id}'";

                            if (isset($_GET["mjesec"]) && $_GET["mjesec"] !== '') {
                                $mjesec = $_GET["mjesec"];
                                $upit .= " AND EXTRACT(MONTH FROM e.datum) = '{$mjesec}'";
                            }

                            if (isset($_GET["godina"]) && $_GET["godina"] !== '') {
                                $godina = $_GET["godina"];
                                $upit .= " AND EXTRACT(YEAR FROM e.datum) = '{$godina}'";
                            }
                        } else {
                            if (isset($_GET["mjesec"]) && $_GET["mjesec"] !== '') {
                                $mjesec = $_GET["mjesec"];
                                $upit .= " WHERE EXTRACT(MONTH FROM e.datum) = '{$mjesec}'";

                                if (isset($_GET["godina"]) && $_GET["godina"] !== '') {
                                  $godina = $_GET["godina"];
                                    $upit .= " AND EXTRACT(YEAR FROM e.datum) = '{$godina}'";
                                }
                            } else {
                                if (isset($_GET["godina"]) && $_GET["godina"] !== '') {
                                    $godina = $_GET["godina"];
                                    $upit .= " WHERE EXTRACT(YEAR FROM e.datum) = '{$godina}'";
                                }
                            }
                        }

                        $upit .= " ORDER BY e.datum ASC";
                        $rezultat = $veza->query($upit);
                    } else {
                        $rezultat = $veza->query($upit);
                    }

                    if ($rezultat->rowCount() > 0) {
                        while ($red = $rezultat->fetch()) {
                            echo "<tr>";
                            echo "<td>{$red['ime']}</td>";
                            echo "<td>{$red['prezime']}</td>";
                            echo "<td style='width:100px'>{$red['datum']}</td>";
                            echo "<td>{$red['vrijeme_pocetka_rada']}</td>";
                            echo "<td>{$red['vrijeme_zavrsetka_rada']}</td>";
                            echo "<td>{$red['sati_zastoja_u_radu']}</td>";
                            echo "<td>{$red['sati_terenskog_rada']}</td>";
                            echo "<td>{$red['sati_pripravnosti']}</td>";
                            echo "<td>{$red['vrijeme_nenazocnosti_u_radu']}</td>";
                            echo "<td>{$red['razlog']}</td>";
                            echo "<td>{$red['ukupno_dnevno_vrijeme_rada']}</td>";
                            echo "<td><a class='poveznica' href='azuriraj_evidenciju.php?id={$red['zaposlenik']}&datum={$red['datum']}'>Ažuriraj</a></td>";
                            echo "<td><a class='poveznica' href='#' onclick='confirmDelete(\"{$red['zaposlenik']}\", \"{$red['datum']}\")'>Obriši</a></td>";

                            echo "</tr>";
                        } 
                    }     
            ?>
            </tbody>
        </table>
    </body>
</html>