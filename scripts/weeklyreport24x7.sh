#!/bin/sh
###########
# Backup for kpionline
# @script:	weeklyreport.sh
# @author: 	Douglas Cristiano Alves
# @mail:	dcalves@br.ibm.com
# @ver:
#	0.1	Initial version
###########
DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
REC="dcalves@br.ibm.com,alimao@br.ibm.com,camita@br.ibm.com,ialamn@br.ibm.com"
CREATION="/tmp/$DATE.kpionline.weeklyreport24x7.xls"
CLOSURE="/tmp/$DATE.kpionline.weekly_closed_kpis24x7.xls"
MSG="/tmp/$DATE.kpionline24x7.msg"

echo "Ticket Num|Description|Category|Category Description|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Month|Day of Week|Time Spent|Servers Qty" > $CREATION
echo "Ticket Num|Description|Category|Category Description|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Month|Day of Week|Time Spent|Servers Qty" > $CLOSURE

#QUERY="SELECT kpis.id, kpis.users_email, kpis.bucket, kpis.agile_group, kpis.user_creator, kpis.creation_date, kpis.closure_date, kpis.time_spent, kpis.description, kpis.num_server, kpis.external_ticket, kpis.status, slas.type, slas.severity, kpis.customers_code, tools.name, categories.name FROM kpis JOIN slas ON kpis.slas_id=slas.id JOIN tools ON kpis.tools_id=tools.id JOIN categories ON kpis.categories_id=categories.id;"

# TO BE RUN SATURDAY 0H
#QUERY="SELECT k.id as 'Ticket Num', k.description as 'Description', ca.name as 'Category', ca.description as 'Category Description', cu.name as 'Customer', s.type as 'Type', k.external_ticket as 'External Ticket', s.severity as 'Priority', k.bucket as 'Bucket', k.user_creator as 'User Creator', k.users_email as 'Assigned to', k.creation_date as 'Creation Date', k.closure_date as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' end as 'Status', strftime('%m', k.creation_date) as 'Month', strftime('%w', k.creation_date) as 'Day of Week', k.time_spent as 'Time Spent' FROM kpis as k 
#		JOIN slas as s on k.slas_id=s.id 
#                JOIN categories as ca on k.categories_id=ca.id 
#                JOIN customers as cu on k.customers_code=cu.code 
#                JOIN tools as t on k.tools_id=t.id 
#WHERE creation_date  >= date('now', '-6 days') AND creation_date < date('now');"
#WHERE k.creation_date >= DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') AND k.creation_date <= DATETIME('now', 'localtime');" 

QUERY1="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.creation_date) as 'Month', strftime('%w', k.creation_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE creation_date  >= date('now', 'localtime', 'weekday 0', '-15 days') AND creation_date < date('now', 'localtime','weekday 0', '-8 days');"

QUERY2="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(ca.description) as 'Category Description', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', strftime('%m', k.closure_date) as 'Month', strftime('%w', k.closure_date) as 'Day of Week', k.time_spent as 'Time Spent', k.num_server as 'Servers Qty' FROM kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id WHERE closure_date  >= date('now', 'localtime', 'weekday 0', '-15 days') AND closure_date < date('now', 'localtime','weekday 0', '-8 days') AND k.status=0;"

/bin/echo $QUERY1 | /usr/bin/sqlite3 $DBPATH >> $CREATION
/bin/echo $QUERY2 | /usr/bin/sqlite3 $DBPATH >> $CLOSURE

echo "Weekly report for kpionline, team 24x7, import in excel with field separator the pipe, '|', and text delimiter the quote '." > $MSG

/bin/mail -s "[$DATE] KPIONLINE - Weekly report for 24x7 (Sat to Fri)" -a $CREATION -a $CLOSURE "$REC" < $MSG

rm -f $CREATION
rm -f $CLOSURE
rm -f $MSG


#EOF