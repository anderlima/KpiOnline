#!/bin/sh
#set -x
###########
# Backup for kpionline
# @script:	weeklyreport.sh
# @author: 	Douglas Cristiano Alves
# @mail:	dcalves@br.ibm.com
# @ver:
#	0.1	Initial version
#	1.0	Stable with analysts necessities
###########
DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
YEAR=`/bin/date +%Y`
#REC="dcalves@br.ibm.com,alimao@br.ibm.com,cbem@br.ibm.com,ibenatti@br.ibm.com,jbpiton@br.ibm.com,ialamn@br.ibm.com,camita@br.ibm.com"
REC="alimao@br.ibm.com"
CREATION="/tmp/$DATE.kpionline.weeklyreport.xls"
CLOSURE="/tmp/$DATE.kpionline.weekly_closed_kpis.xls"
MSG="/tmp/$DATE.kpionline.msg"

echo "Ticket Num|Description|Category|Category Description|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Month|Day of Week|Time Spent|Servers Qty" > $CREATION
echo "Ticket Num|Description|Category|Category Description|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Month|Day of Week|Time Spent|Servers Qty" > $CLOSURE

#QUERY1="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.creation_date) as 'Month', strftime('%w', k.creation_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE creation_date  >= date('now', 'localtime', 'weekday 0', '-15 days') AND creation_date < date('now', 'localtime','weekday 0', '-8 days');"
QUERY1="SELECT quote(k.id) as 'Ticket Num', replace(k.description,x'0D0A','') as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.creation_date) as 'Month', strftime('%w', k.creation_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE creation_date BETWEEN '$YEAR-01-01 00:00:00' AND date('now');"

#QUERY2="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.closure_date) as 'Month', strftime('%w', k.closure_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE closure_date  >= date('now', 'localtime', 'weekday 0', '-15 days') AND closure_date < date('now', 'localtime','weekday 0', '-8 days') AND k.status=0;"
QUERY2="SELECT quote(k.id) as 'Ticket Num', replace(k.description,x'0D0A','') as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.closure_date) as 'Month', strftime('%w', k.closure_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE closure_date  >= date('now', 'localtime', 'weekday 0', '-15 days') AND closure_date < date('now', 'localtime','weekday 0', '-8 days') AND k.status=0;"

/bin/echo $QUERY1 | /usr/bin/sqlite3 $DBPATH >> $CREATION
/bin/echo $QUERY2 | /usr/bin/sqlite3 $DBPATH >> $CLOSURE

#echo "Weekly report for kpionline, import in excel with field separator the pipe, '|', and text delimiter the quote '" > $MSG

#/bin/mail -s "[$DATE] KPIONLINE - Weekly report (Sat to Fri)" -a $CREATION -a $CLOSURE "$REC" < $MSG

echo "Weekly report with year information for watson usage." > $MSG

/bin/mail -s "[$DATE] KPIONLINE - Weekly report (Sat to Fri) for Watson" -a $CREATION -a $CLOSURE  "$REC" < $MSG

rm -f $CREATION
rm -f $CLOSURE
rm -f $MSG


#EOF

