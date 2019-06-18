#!/bin/sh
##
# Backup solution
# @script:	kpionlinebkp.sh
# @author: 	Douglas Cristiano Alves
# @mail:	dcalves@br.ibm.com
# @ver:
#	0.1	Initial version
###########
DATE=`/bin/date +%Y%m%d`
BACKUPPATH="/gdbr/kpionline/backup"
DBACKUPPATH="$BACKUPPATH/daily"
MBACKUPPATH="$BACKUPPATH/monthly"
SYSPATH="/gdbr/kpionline/docs"
REC="dcalves@br.ibm.com,alimao@br.ibm.com,ieaubr4@br.ibm.com"

#DAILY BACKUP#
tar -cvf $DBACKUPPATH/$DATE.kpionlinedb.tar $SYSPATH/*.sqlite
echo "$DATE;Weekly backup;$DBACKUPPATH/$DATE.kpionlinedb.tar written" >> $BACKUPPATH/backuplog.log
#LOG ROTATION EACH DAY, REMOVING FROM LAST WEEK
find $DBACKUPPATH -mtime +7 -exec rm {} \;
echo "$DATE;Weekly backup;Log rotation done, removed files from older than 7 days" >> $BACKUPPATH/backuplog.log

#MONTLY BACKUP AND ROTATION
if [ `echo $DATE | cut -c 7-` == "01" ]
then
	#MONTHLY BACKUP#
	tar -cvf $MBACKUPPATH/$DATE.kpionlinedb.tar $SYSPATH/*.sqlite #YES, WE DUPLICATE THE MONTHLY BACKUP BY PURPOSE
	echo "$DATE;Monthly backup;$MBACKUPPATH/$DATE.kpionlinedb.tar written" >> $BACKUPPATH/backuplog.log
	#MAIL BACKUP
	echo "Segue arquivo do mês de backup do banco de dados do sistema. Enjoy" > /tmp/msg
	#/bin/mail -s "[$DATE] KPIONLINE - Monthly backup" -a /tmp/msg "$REC" < $MBACKUPPATH/$DATE.kpionlinedb.tar	
	/bin/mail -s "[$DATE] KPIONLINE - Monthly backup" -a $MBACKUPPATH/$DATE.kpionlinedb.tar "$REC" < /tmp/msg
	echo "$DATE;Monthly backup;email to $REC sent" >> $BACKUPPATH/backuplog.log
	#full backup by e-mail
	MONTH=`/bin/date +%Y%m%d | cut -c 5,6`; STATUS=$(( $MONTH % 2 ))
	#STATUS=0
	if [ $STATUS -eq 0 ]
	then 
		tar -cvf $MBACKUPPATH/$DATE.kpionlinefull.tar $SYSPATH
		echo "$DATE;Bi Montly backup;Full backup done" >> $BACKUPPATH/backuplog.log
		echo "Segue backup completo (código e banco) do sistema. Enjoy" > /tmp/msg
		#/bin/mail -s "[$DATE] KPIONLINE - BiMonthly backup" -a /tmp/msg "$REC" < $MBACKUPPATH/$DATE.kpionlinefull.tar
		/bin/mail -s "[$DATE] KPIONLINE - BiMonthly backup" -a $MBACKUPPATH/$DATE.kpionlinefull.tar  "$REC" < /tmp/msg
		echo "$DATE;Bi Montly bakcup;email to $REC sent" >> $BACKUPPATH/backuplog.log
	fi
	#LOG ROTATION EACH 6 MONTHS
	find $DBACKUPPATH -mtime +180 -exec rm {} \;
	echo "$DATE;Monthly backup;Log roration done, removed files older than 6 months" >> $BACKUPPATH/backuplog.log
fi

#fix permission
/bin/chown -R :www $BACKUPPATH
/bin/chmod -R 775 $BACKUPPATH

#EOF

