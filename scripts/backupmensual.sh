 #!/bin/sh


 cd '/home/connectus/public_html/backups/backupmensual/'
 
 mysqldump -h localhost -u 'connectus' -p'IFDMCKCV92MFVC' --all-databases  > respaldo_completo.sql 
 tar -zcvf respaldo_$(date +%d%m%y).tgz *.sql
 find -name '*.tgz' -type f -mtime +365 -exec rm -f {} ;

## Instalacion
#1) Cambiar los permisos
#  sudo chmod 700 backupmensual.sh
