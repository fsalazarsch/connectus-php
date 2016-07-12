 #!/bin/sh


 cd '/home/connectus/public_html/backups/'
 
 mysqldump -h localhost -u 'connectus' -p'IFDMCKCV92MFVC' --all-databases  > respaldo_completo.sql 
 tar -zcvf respaldo_$(date +%d%m%y).tgz *.sql

## OPCIONAL NUMERO MAXIMO DE DIAS QUE ESTARÁ EL BACKUP, POR DEFECTO 5 DIAS
find -name '*.tgz' -type f -mtime +5 -exec rm -f {} ;
 
## Instalacion
#1) Cambiar los permisos
#  sudo chmod 700 backup.sh

#2) Añadir el Script a CRON
 #Abrir crontab, ejecutar
  #crontab -e
 
 #3) Agregar que se ejcute a las 1:00 A.M. todos los dias
 #0 1 * * * /public_html/scripts/backup.sh
