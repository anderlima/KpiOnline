#!/bin/sh
###########
# Dispatcher mail reports
# @script:	dispmailreport.sh
# @author: 	Douglas Cristiano Alves
# @mail:	dcalves@br.ibm.com
# @ver:
#	0.1	Initial version
###########
DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
REC="dcalves@br.ibm.com,alimao@br.ibm.com,ieaubr4@br.ibm.com,jbpiton@br.ibm.com,dfpf@br.ibm.com,ialamn@br.ibm.com"
#REC="alimao@br.ibm.com"
TMP="/tmp/$DATE.kpionline.dispmailreport.xls"
MSG="/tmp/$DATE.dispmailreport.msg"
echo > $TMP

# NUMBER OF STATUS OPEN KPIS
echo "# TOTAL OF STATUS=OPEN KPIS" >> $TMP
echo "Total opened|Analyst" >> $TMP
QUERY="select count(*), users_email from kpis where status=1 group by users_email;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP


# NUMBER OF STATUS OPEN KPIS FOR THE DAY
echo "# TOTAL OF STATUS=OPEN KPIS WITH SLA FOR TODAY PER ANALYST" >> $TMP
echo "Total opened|Analyst" >> $TMP
QUERY="select count(*),users_email from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', 'start of day', '+24 hours') - strftime('%s', k.creation_date)) and status=1 and creation_date between datetime('now', 'start of day') and datetime('now', 'start of day', '+24 hours') group by users_email;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP


# LIST OF STATUS OPEN KPIS FOR THE DAY
echo "# DETAILED OF STATUS=OPEN KPIS WITH SLA FOR TODAY PER ANALYST" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', 'start of day', '+24 hours') - strftime('%s', k.creation_date)) and status=1 and creation_date between datetime('now', 'start of day') and datetime('now', 'start of day', '+24 hours') order by users_email;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP


# LIST OF SLA BREACHED AND STATUS OPEN KPIS
echo "# LIST OF SLA BREACHED AND STATUS OPEN KPIS" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description), (s.sla*60), (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP


# 8 HOURS
echo "# LIST OF TICKETS WITH SLA <= 8hs" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description),(s.sla*60),(strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', '+8 hours') - strftime('%s', k.creation_date)) and (s.sla*60) > (strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) and not((s.sla*60) >= (strftime('%s', 'now', '+8 hours') - strftime('%s', k.creation_date))) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP

# 24HS
echo "# LIST OF TICKETS WITH SLA <= 24hs" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description),(s.sla*60),(strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', '+24 hours') - strftime('%s', k.creation_date)) and (s.sla*60) > (strftime('%s', 'now', 'localtime', '+8 hours') - strftime('%s', k.creation_date)) and not((s.sla*60) >= (strftime('%s', 'now', '+24 hours') - strftime('%s', k.creation_date))) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP

# 48HS
echo "# LIST OF TICKETS WITH SLA <= 48hs" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description),(s.sla*60),(strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', '+48 hours') - strftime('%s', k.creation_date)) and (s.sla*60) > (strftime('%s', 'now', 'localtime','+24 hours') - strftime('%s', k.creation_date)) and not((s.sla*60) >= (strftime('%s', 'now', '+48 hours') - strftime('%s', k.creation_date))) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP

# 72HS
echo "# LIST OF TICKETS WITH SLA <= 72hs" >> $TMP
echo "KPInum|Analyst|Bucket|Group|Created time|External Number|Account|Description|SLA(s)|Elapsed time(s)" >> $TMP
QUERY="select k.id,k.users_email,k.bucket,k.agile_group,k.creation_date,quote(k.external_ticket),k.customers_code,quote(k.description),(s.sla*60),(strftime('%s', 'now', 'localtime') - strftime('%s', k.creation_date)) from kpis as k join slas as s on k.slas_id=s.id where (s.sla*60) < (strftime('%s', 'now', 'localtime', '+72 hours') - strftime('%s', k.creation_date)) and (s.sla*60) > (strftime('%s', 'now', 'localtime','+48 hours') - strftime('%s', k.creation_date)) and not((s.sla*60) >= (strftime('%s', 'now', '+72 hours') - strftime('%s', k.creation_date))) and status=1;"
/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH >> $TMP
echo >> $TMP
echo >> $TMP

#EMAIL
echo "Dispatching daily report for kpionline, open in excel with field separator the pipe, '|', and text delimiter the quote '." > $MSG
/bin/mail -s "[$DATE] KPIONLINE - Dispatching daily mail report" -a $TMP "$REC" < $MSG

rm -f $TMP
rm -f $MSG

#EOF
