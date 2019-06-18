#!/bin/sh
###########
# Warning about 0 kpis closed to L1
# @script:      zero_warning.sh
# @author:      Anderson Lima de Oliveira
# @mail:        alimao@br.ibm.com
###########

DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
TMP="/tmp/$DATE.kpionline.zero_warning.txt"
MSG="/tmp/$DATE.zero_warning.msg"
REC="alimao@br.ibm.com,ieaubr4@br.ibm.com,bcchaves@br.ibm.com"

QUERY="select distinct u.email from kpis as k join users as u on k.users_email=u.email where k.users_email not in (select users_email from kpis where closure_date between datetime('now', '-1 days', 'start of day') and datetime('now', '-1 days', 'start of day', '+24 hours')) and u.bucket='L1';"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP

for i in `cat $TMP`
do
REC="${REC},${i}"
done

echo -e "You did not close any KPI yesterday.\nPlease make sure dispatcher and DA are aligned about this current situation." > $MSG

/bin/mail -s "[$DATE] KPIONLINE - Warning - Daily Performance Check" "$REC" < $MSG

rm $TMP
rm $MSG
