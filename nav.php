<nav>
<?php 
    if(!isset($_SESSION["korime"])){
        echo "<a class='poveznica' href='index.php'>Početna</a>";
        echo "<a class='poveznica' href='prikaz_place.php'>Pregled plaće</a>";
        echo "<a class='poveznica' href='prijava.php'>Prijava</a>";
    }else{
        echo "<a class='poveznica' href='evidencija_rada.php'>Evidentiraj rad korisnika</a>";
        echo "<a class='poveznica' href='prikaz_evidencije.php'>Evidencija</a>";
        echo "<a class='poveznica' href='bruto.php'>Izračun bruto</a>";
        echo "<a class='poveznica' href='neto.php'>Izračun neto</a>";
        echo "<a class='poveznica' href='odjava.php'>Odjava</a>";   
    }
?>
</nav>