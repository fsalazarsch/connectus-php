<?php
    
    error_reporting(E_ALL);


    $dbhost =   "localhost";
    $dbuser =   "connectu_test";
    $dbpass =   "Connectus.2016;";
    $dbname =   "connectu_connecta_test";
   
   $backup_file = date("Y-m-d-H-i-s")."_".$dbname.'.sql';

                       #mysqldump --opt -Q -u dbusername --password=dbpassword dbname | gzip > /path-to-store-the-backup-file/db_backup.sql.gz
   $command = "/usr/bin/mysqldump --opt -Q -u ".$dbuser." -password=".$dbpass." -h ".$dbhost." database connectu_connecta_test > ".$backup_file;


    echo "<pre>";

   try{
        
        shell_exec($command);
   
    }catch (Exception $e) {
        echo( "Caught exception: " . $e->getMessage() );
    }
   

   var_dump($output);

   echo "<br><br><br>".$command;


?> 