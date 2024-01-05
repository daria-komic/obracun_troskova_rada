<?php
    function veza_na_bazu(){
        $dsn = "pgsql:host=localhost;dbname=TBP_Aplikacija_za_temporalno_mjerenje_i_obracun_troskova_rada;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false    
        ];
        $veza = new PDO($dsn,'postgres','komic',$opt);
        return $veza;
    }
?>
