#!/bin/bash

# This script is for building
# all stack variations and check for errors
# during the mysqli and pdo connect.
# This Script is build for Linux
# Info:
# This Script works on WSL2 _but_ you cant use
# WSL2 Windows Host mounted paths for the data.

dc=$(which docker-compose)
osversion=$(uname)
dbarr=(mariadb103 mariadb104 mariadb105 mariadb106 mysql57 mysql8)

checkdep() {

echo "### checking dependencies"
which docker || { echo 'Executable not found: docker' ; exit 1; }
which docker-compose || { echo 'Executable not found: docker-compose' ; exit 1; }
which curl || { echo 'Executable not found: curl' ; exit 1; }
which sed || { echo 'Executable not found: sed' ; exit 1; }
}

usage() {

echo "Usage:"
echo "       -b = build all container variations of specified version"
echo "            valid values are: php54, php56, php71, php72, php73, php74, php8, php81, php82"
echo -e " \nAttention: !!! SCRIPT REMOVES ALL DATA IN 'data/mysql/*' !!!"
}

# build stack variations
build () {

        echo "### building $buildtarget-$version"

                # removing old mysql data, old data prevents mysql
                # from starting correct
                echo -e "### cleaning old mysql data"
                rm -rf ./data/mysql/*
                echo -e "### building ./buildtarget/$buildtarget-$version.env \n"
                $dc --env-file ./buildtest/$buildtarget-$version.env up -d --build
                # wait for mysql to initialize
                sleep 30
                # check definitions
                curlmysqli=$(curl -s --max-time 15 --connect-timeout 15 http://localhost/test_db.php |grep proper |wc -l |tr -d '[:space:]')
                curlpdo=$(curl -s --max-time 15 --connect-timeout 15 http://localhost/test_db_pdo.php |grep proper |wc -l |tr -d '[:space:]')

                        # check if we can create a successfull connection to the database
                        # 1=OK  everything else is not ok
                        if [ "$curlmysqli" -ne "1" ]; then
                                echo -e "### ERROR: myqli database check failed expected string 'proper' not found \n"
                                echo "### ...stopping container"
                                $dc --env-file ./buildtest/$buildtarget-$version.env down
                                exit
                        else
                                echo -e "\n OK - mysqli database check successfull \n"
                                sleep 3
                        fi

                        if [ "$curlpdo" -ne "1" ]; then
                                echo -e "### ERROR: pdo database check failed expected string 'proper' not found \n"
                                echo "### ...stopping container"
                                $dc --env-file ./buildtest/$buildtarget-$version.env down
                                exit
                        else
                                echo -e "\n OK - pdo database check successfull \n"
                                sleep 3
                        fi

                echo "### ... stopping container"
                $dc --env-file ./buildtest/$buildtarget-$version.env down
}

buildenvfile () {

cat sample.env > ./buildtest/"$buildtarget"-"$version".env
sed -i "s/COMPOSE_PROJECT_NAME=lamp/COMPOSE_PROJECT_NAME=$buildtarget-buildtest/" ./buildtest/"$buildtarget"-"$version".env
sed -i "s/PHPVERSION=php8/PHPVERSION=$buildtarget/" ./buildtest/"$buildtarget"-"$version".env
sed -i "s/DATABASE=mysql8/DATABASE=$version/" ./buildtest/"$buildtarget"-"$version".env
}

prepare () {

# generate all .env files for building
echo "### building env file"
mkdir -p ./buildtest
rm -rf ./buildtest/"$buildtarget"*
}

cleanup () {

echo "### cleaning old env file"
rm -rf ./buildtest/"$buildtarget"*
}

while getopts ":b:" opt;
do
        case "${opt}" in
                b) buildtarget=${OPTARG};;
        esac
        no_args="false"
done

# check user input
[[ "$no_args" == "true" ]] && { usage; exit 1; }

# check if we are running on Linux
if [[ $osversion != 'Linux' ]]; then
        echo "This Script only supports Linux sorry :("
        exit
fi

# we don't want to test against mysql8 for the old
# php versions due to pdo, therefore we only
# take the first 5 elements of the database versions arrays

if [ "$buildtarget" == 'php54' ]||[ "$buildtarget" == 'php56' ]||[ "$buildtarget" == 'php71' ]||\
   [ "$buildtarget" == 'php72' ]||[ "$buildtarget" == 'php73' ] ; then
        for version in "${dbarr[@]:0:5}"
        do
                checkdep
                prepare
                buildenvfile "$buildtarget" "$version"
                build "$buildtarget" "$version"
                cleanup
        done
elif [ "$buildtarget" == 'php74' ]||[ "$buildtarget" == 'php8' ]||[ "$buildtarget" == 'php81' ] || [ "$buildtarget" == 'php82' ] || [ "$buildtarget" == 'php83' ] ; then
        for version in "${dbarr[@]}"
        do
                checkdep
                prepare
                buildenvfile "$buildtarget" "$version"
                build "$buildtarget" "$version"
                cleanup
        done
else
        echo "Input not valid"
        usage
fi

exit