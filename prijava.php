<?php
    session_start();
    include_once("baza.php");
    $veza = veza_na_bazu();

	if(isset($_POST["submit"])){
        $korime = $_POST["korime"];
        $lozinka = $_POST["lozinka"];
        $prijava = FALSE;

        if(empty($_POST["korime"]) || empty($_POST["lozinka"])){
            echo "Obavezan unos korisničkog imena i lozinke!";
        }else{
            $upit = "SELECT * FROM korisnik WHERE korime = '{$korime}' AND lozinka = '{$lozinka}'";
            $rezultat = $veza->query($upit);
            $red = $rezultat->fetchAll();
            if(!empty($red)){
                $prijava = TRUE;
            }
        }
        if($prijava){
            $_SESSION["korime"] = $red[0]["korime"];
            $_SESSION["uloga"] = $red[0]["uloga"];
            $_SESSION["ime"] = $red[0]["ime"];
            header('Location:index.php');
        }else{
            echo "Autentikacija nije uspješna!";
        }

    }

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Prijava</title>
		<meta name="autor" content="Komic Daria"/>
		<meta name="datum" content="30.12.2023."/>
		<meta charset="utf-8"/>
        <link href="stil.css" rel="stylesheet" type="text/css">
	</head>
	<body>
        <div>
            <h2>Prijava</h2>
            <form action="prijava.php" method="POST">
            <label for="korime">Korisničko ime:</label> <br>
            <input type="text" id="korime" name="korime" required=""><br><br>
            <label for="lozinka">Lozinka:</label><br>
            <input type="password" name="lozinka" id="lozinka"><br><br>
            <input type="submit" name="submit" value="Prijavi se"> 
            </form>
        </div>
    </body>
</html>
