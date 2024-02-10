#!/bin/bash

for yml in `find *.yml`
do
	i=$((i+1))
	allyml[$i]=$yml
	if [ -z $1 ]; then
		echo "${i}. ${yml}"
	fi
done

if [ -z $1 ]; then
	read -p "Select yml: " select_yml
else
	let "select_yml = $1"
fi

current_yml=${allyml[${select_yml}]}

if [ -z $2 ]; then
	echo
	echo "1. up"
	echo "2. down"
	echo "3. restart"
	echo "4. build"
	echo "5. pull"
	echo "6. Enter in webserver container"
	echo "7. Enter in database container"
	echo "8. Enter in postgres container"
	echo "9. Enter in redis container"
	read -p "Select command: " select_command
else
	let "select_command = $2"
fi

case $select_command in
1) docker-compose -f ${current_yml} up -d --remove-orphans;;
2) docker-compose -f ${current_yml} down --remove-orphans;;
3) docker-compose -f ${current_yml} restart;;
4) docker-compose -f ${current_yml} build --no-cache;;
5) docker-compose -f ${current_yml} pull;;
6) docker-compose -f ${current_yml} exec webserver bash;;
7) docker-compose -f ${current_yml} exec database bash;;
8) docker-compose -f ${current_yml} exec postgres bash;;
9) docker-compose -f ${current_yml} exec redis bash;;
*) echo "Unknow command";;
esac
