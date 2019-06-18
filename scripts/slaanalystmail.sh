#!/bin/sh
#set -x
###########
# Dispatcher mail reports
# @script:      slaanalystmail.sh
# @author:      Douglas Cristiano Alves
# @mail:        dcalves@br.ibm.com
# @ver:
#       0.1     Initial version
###########
DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
#HOURS="/tmp/$DATE.kpionline.8hours.out"
BREACH="/tmp/$DATE.kpionline.breached.out"
MAIL="/tmp/$DATE.kpionline.analystmail.xls"
MSG="/tmp/$DATE.kpionline.analystmail.msg.xls"

# LIST OF SLA BREACHED AND STATUS OPEN KPIS
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description), (s.sla*60), (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH > $BREACH

# 8 HOURS
#QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description),(s.sla*60),(strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', '+24 hours') - strftime('%s', k.creation_date)) and (s.sla*60) > (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) and not((s.sla*60) >= (strftime('%s', 'now', '+24 hours') - strftime('%s', k.creation_date))) and status=1;"
#/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH > $HOURS

for i in `cat $BREACH | awk -F'|' '{print $2}' | sort -n | uniq`
do
	if [[ $i == *"@"* ]]; then
        echo "SLA report for kpionline, open in excel with field separator the pipe, '|'." > $MSG
        echo >> $MSG
        echo "Quick info: " >> $MSG

        echo > $MAIL
        echo "SLA BREACHED" >> $MAIL
        echo "SLA BREACHED" >> $MSG
        echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $MAIL
        echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $MSG
        grep $i $BREACH >> $MAIL
        grep $i $BREACH >> $MSG
        echo >> $MAIL
        echo >> $MAIL
        echo >> $MSG

        #echo "SLA NEAR - 24hs" >> $MAIL
        #echo "SLA NEAR - 24hs" >> $MSG
        #echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $MAIL
        #echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $MSG
        #grep $i $HOURS >> $MAIL
	#grep $i $HOURS >> $MSG


        /bin/mail -s "[$DATE] KPIONLINE - SLA BREACHED for  $i" -a $MAIL "dcalves@br.ibm.com,ieaubr4@br.ibm.com,jbpiton@br.ibm.com,alimao@br.ibm.com,$i" < $MSG
#	/bin/mail -s "[$DATE] KPIONLINE - SLA BREACHED for  $i" -a $MAIL "alimao@br.ibm.com" < $MSG
fi
done

#rm -f $HOURS $BREACH $MAIL $MSG
rm -f $BREACH $MAIL $MSG

#EOF
