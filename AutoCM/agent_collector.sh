#!/bin/bash

###########
# @Description
# @script:      agent_collector.sh
# @author:      Anderson Lima de Oliveira
# @mail:        alimao@br.ibm.com
# @Version: 	0.1
###########

today=$(date +%m.%d.%y)
day=$(date +%d-%m-%y)
SDIR='/gdbr/kpionline/AutoCM' #dir where script resides
DBPATH="$SDIR/data/autocm.sqlite"
LOG="$SDIR/logs-$day"
FDIR='/nwsbr/hc/cgi-bin/data/tivoli' #leave this way
TDIR='/nwsbr/hc/cgi-bin/history/trend-day'
accounts=$(cat $SDIR/accounts)

/bin/echo "DELETE FROM agents;" | /usr/bin/sqlite3 $DBPATH

for customer in $accounts
do
	account=$(echo $customer | cut -d_ -f1)
	file=$(ls -Art $FDIR/$customer |tail -n 1)
	if [ "$file" != "hc.$today.txt" ]
	then
		outdated='SMAT Outdated for $customer on $day' >> $LOG
	else
		rfile=$(ls -Art $FDIR/$customer/ |tail -n 1) #mudar para TDIR quando for usar o usuario smat e passar pela exclude list
		cat $FDIR/$customer/$rfile > /tmp/agents
			cat /tmp/agents |while read -r line
			do
				agent=$(echo $line |cut -d@ -f1)
				amount=$(echo $line |cut -d@ -f1 | grep -o ":" |wc -l)
				if [[ $amount -eq 2 ]]
				then
					hostname=$(echo $line |cut -d@ -f1 |cut -d: -f2)
				else
					hostname=$(echo $line |cut -d@ -f1 |cut -d: -f1)
				fi
				pc=$(echo $line |cut -d@ -f2 |awk '{print $1}')
				os=$(echo $line |cut -d@ -f2 |awk '{print $2}')
				ip=$(echo $line |cut -d@ -f3)
				version=$(echo $line |cut -d@ -f4)
				status=$(echo $line |cut -d@ -f5)
				statusfull=$(echo $line |cut -d@ -f6)
				remotetems=$(echo $line |cut -d@ -f7)
				
				QUERY="INSERT INTO agents (account, agent, hostname, pc, os, ip, version, status, statusfull, remotetems, cdate) 
				VALUES ('$account', '$agent', '$hostname', '$pc', '$os', '$ip', '$version', '$status', '$statusfull', '$remotetems', '$day');"
				
				/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH

			done
					
	fi
done

#EOF
