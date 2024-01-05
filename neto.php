<?php 
    session_start();
    include_once("baza.php");
    $veza = veza_na_bazu();
    if (isset($_GET["id"]) && $_GET["godina"] && $_GET["mjesec"]) {
        $zaposlenik = $_GET["id"];
        $mjesec = $_GET["mjesec"];
        $godina = $_GET["godina"];
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
    <script>
        function confirmDelete(zaposlenik, mjesec, godina) {
        var result = confirm("Jeste li sigurni da želite obrisati ovaj redak?");

            if (result) {
                window.location.href = `obrisi_neto.php?id=${zaposlenik}&mjesec=${mjesec}&godina=${godina}`; 
            }
        }

    </script>
    <?php
        include_once("nav.php");
    ?>
    <body>
        <?php
        if(isset($_SESSION["korime"])){ 
            echo "<div  style='float:right;'>Pozdrav, " . $_SESSION["ime"] . "! </div>"; }
        ?>
        <h1>Izračun neto plaće po korisniku</h1>
        <br>
            <form method="get" action="<?php echo $_SERVER["PHP_SELF"];?>">
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
                <br>
                <br>
                <label for="mjesec">Mjesec</label>
                <br>
                <input type="text" placeholder="Upišite mjesec ovdje" id="mjesec" name="mjesec" required/>
                <br>
                <br>
                <label for="mjesec">Godina</label>
                <br>
                <input type="text" placeholder="Upišite godinu ovdje" id="godina" name="godina" required/>
                <br>
                <br>
                <input name="submit" type="submit" value="Izračunaj neto"/>
                
            </form>
            <br>
        <table>
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Mjesec</th>
                    <th>Godina</th>
                    <th>Iznos neto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php  
                
                    if(isset($_GET["zaposlenik"]) && isset($_GET["mjesec"]) && isset($_GET["godina"])){
                        try{
                            $id = $_GET["zaposlenik"];
                            $mjesec = $_GET["mjesec"];
                            $godina = $_GET["godina"];
    
                            $upit = "INSERT INTO neto_placa (zaposlenik, mjesec, godina)  VALUES (?, ?, ?)";
                           
                            $rezultat = $veza->prepare($upit);

                            $rezultat->bindParam(1, $id);
                            $rezultat->bindParam(2, $mjesec);
                            $rezultat->bindParam(3, $godina);

                            $rezultat->execute();

                            echo "<br> Evidencija neto plaće za " . $mjesec . " mjesec uspješno kreirana.";

                        }catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    }
                    
                    $upit = "SELECT 
                    n.zaposlenik,
                    z.ime,
                    z.prezime,
                    n.mjesec,
                    n.godina,   
                    n.iznos
                    FROM neto_placa n
                    LEFT JOIN zaposlenik z ON n.zaposlenik = z.oib
                    ORDER BY n.zaposlenik";

                    $rezultat = $veza->query($upit);

                    if ($rezultat->rowCount() > 0) {
                        while ($red = $rezultat->fetch()) {
                            echo "<tr>";
                            echo "<td>{$red['ime']}</td>";
                            echo "<td>{$red['prezime']}</td>";
                            echo "<td>{$red['mjesec']}</td>";
                            echo "<td>{$red['godina']}</td>";
                            echo "<td>{$red['iznos']}</td>";
                            echo "<td><a class='poveznica' href='#' onclick='confirmDelete(\"{$red['zaposlenik']}\", \"{$red['mjesec']}\", \"{$red['godina']}\")'>Obriši</a></td>";
                            echo "</tr>";
                        } 
                    }     
            ?>
            </tbody>
        </table>
    </body>
</html>