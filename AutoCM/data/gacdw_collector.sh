#!/bin/bash

###########
# @Description
# @script:      gacdw_collector.sh
# @author:      Anderson Lima de Oliveira
# @mail:        alimao@br.ibm.com
# @Version: 	0.1
###########

today=$(date +%m.%d.%y)
day=$(date +%d-%m-%y)
SDIR='/gdbr/kpionline/AutoCM' #dir where script resides
DBPATH="$SDIR/data/autocm.sqlite"
LOG="$SDIR/logs/autocm-$day"
CSV="$SDIR/data/afi_gacdw.csv"


			cat $CSV |while read -r line
			do
				chipid=$(echo $line |cut -d, -f1)
				acct=$(echo $line |cut -d, -f2)
				hostname=$(echo $line |cut -d, -f3)
				fqhn=$(echo $line |cut -d, -f4)
				cilifecyclestate=$(echo $line |cut -d, -f5)
				ip=$(echo $line |cut -d, -f6)
				platform=$(echo $line |cut -d, -f7)
				timestamp=$(echo $line |cut -d, -f8)
				environment=$(echo $line |cut -d, -f9)
				
				QUERY="INSERT INTO gacdw (chipid, acct, hostname, fqhn, cilifecyclestate, ip, platform, timestamp, environment) 
				VALUES ('$chipid', '$acct', '$hostname', '$fqhn', '$cilifecyclestate', '$ip', '$platform', '$timestamp', '$environment');"
				
				/bin/echo $QUERY | /usr/bin/sqlite3 $DBPATH

			done
#EOF
