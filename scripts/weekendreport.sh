#!/bin/sh
###########
# Summary of Fridays KPIs and closed on weekend
# @script:      weekendreport.sh
# @author:      Anderson Oliveira (based on dcalves@ weeklyreport script)
###########

DATE=`/bin/date +%Y%m%d`
DBPATH="/gdbr/kpionline/docs/kpionline.sqlite"
REC="dcalves@br.ibm.com,alimao@br.ibm.com,jbpiton@br.ibm.com,ialamn@br.ibm.com,bcchaves@br.ibm.com"
FRIDAY="/tmp/$DATE.kpionline.fridayqueue.xls"
WEEKEND="/tmp/$DATE.kpionline.closedonweekend.xls"
MSG="/tmp/$DATE.weekendreport.msg"

echo "Ticket Num|Description|Category|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Servers Qty" > $FRIDAY
echo "Ticket Num|Description|Category|Customer|Type|External Ticket|Priority|Bucket|User Creator|Assigned to|Creation Date|Closure Date|Status|Servers Qty" > $WEEKEND

QUERY1="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', k.num_server as 'Servers Qty' from kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id where k.creation_date <= DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') and k.closure_date between DATETIME('now', 'localtime', 'weekday 0', '-7 days', 'start of day') and DATETIME('now', 'localtime') or status=1 and k.creation_date <= DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') order by k.users_email;"

QUERY2="SELECT quote(k.id) as 'Ticket Num', quote(k.description) as 'Description', quote(ca.name) as 'Category', quote(cu.name) as 'Customer', quote(s.type) as 'Type', quote(k.external_ticket) as 'External Ticket', quote(s.severity) as 'Priority', quote(k.bucket) as 'Bucket', quote(k.user_creator) as 'User Creator', quote(k.users_email) as 'Assigned to', quote(k.creation_date) as 'Creation Date', quote(k.closure_date) as 'Closure Date', case k.status when 3 then 'Audit' when 0 then 'Closed' when 1 then 'Open' when 2 then 'Deleted' when 3 then 'Audit' end as 'Status', k.num_server as 'Servers Qty' from kpis as k JOIN slas as s on k.slas_id=s.id JOIN categories as ca on k.categories_id=ca.id JOIN customers as cu on k.customers_code=cu.code JOIN tools as t on k.tools_id=t.id where k.closure_date between DATETIME('now', 'localtime', 'weekday 0', '-8 days', 'start of day') and DATETIME('now', 'localtime', 'weekday 0', '-6 days', 'start of day') order by k.users_email;"


/bin/echo $QUERY1 | /usr/bin/sqlite3 $DBPATH >> $FRIDAY
/bin/echo $QUERY2 | /usr/bin/sqlite3 $DBPATH >> $WEEKEND

echo "Weekend report for kpionline - Summary of Fridays KPIs and closed on weekend - Import in excel with field separator the pipe, '|', and text delimiter the quote '." > $MSG

/bin/mail -s "[$DATE] KPIONLINE - Weekend Report " -a $FRIDAY -a $WEEKEND "$REC" < $MSG

rm -f $FRIDAY
rm -f $WEEKEND
rm -f $MSG

#EOF
