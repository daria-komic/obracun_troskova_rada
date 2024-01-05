# obracun_troskova_rada
tbp-aplikacija_za_obracun_troskova_rada

Aplikacija za obračun troškova rada izrađena u PostgreSQL-u sa Web grafičkim sučeljem.

Za instalaciju na Linuxu potrebno je:

- instalirati ```postgresql```
- instalirati php sa naredbom ```sudo apt-get install php```
- instalirati pgsql naredbom ```sudo apt-get install php-pgsql```
- kreirati novu bazu (ime baze odgovara onoj u ```baza.php```) ```createdb TBP_Aplikacija_za_temporalno_mjerenje_i_obracun_troskova_rada ```
- učitati bazu iz backupa ```pg_restore --dbname=TBP_Aplikacija_za_temporalno_mjerenje_i_obracun_troskova_rada --no-password --format=custom --single-transaction -if-exists --clean /path/to/backup/file```
- otvoriti lokalni server ```php -S localhost:8000```
- po potrebi promijeniti korisnika i lozinku u skripti ```baza.php``` da odgovara onom korisniku koji je kreirao bazu 

Nakon instalacije i pokretanja lokalnog servera, aplikaciji je moguće pristupiti kroz web preglednik na lokaciji localhost:8000

Za prijavu u aplikaciju može se koristiti: admin 123456

*Sve datoteke i backup se trebaju nalaziti u direktoriju gdje se otvara lokalni server.

