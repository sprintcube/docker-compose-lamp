#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")
readonly IP=$2

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add the VM IP to the list of allowed IPs"
awk -v ip=$IP -f /app/vagrant/provision/provision.awk /app/config/web.php

info "Prepare root password for MySQL"
debconf-set-selections <<< 'mariadb-server mysql-server/root_password password'
debconf-set-selections <<< 'mariadb-server mysql-server/root_password_again password'
echo "Done!"

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.2-curl php7.2-cli php7.2-intl php7.2-mysqlnd php7.2-gd php7.2-fpm php7.2-mbstring php7.2-xml unzip nginx mariadb-server-10.1 php.xdebug

info "Configure MySQL"
sed -i 's/.*bind-address.*/bind-address = 0.0.0.0/' /etc/mysql/mariadb.conf.d/50-server.cnf
mysql <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql <<< "DROP USER 'root'@'localhost'"
mysql <<< 'FLUSH PRIVILEGES'
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.2/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Removing default site configuration"
rm /etc/nginx/sites-enabled/default
echo "Done!"

info "Initailize databases for MySQL"
mysql <<< 'CREATE DATABASE yii2basic'
mysql <<< 'CREATE DATABASE yii2basic_test'
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
