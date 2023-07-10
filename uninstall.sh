#!/bin/bash

# Uninstall script for Dashlinx

echo "Starting uninstall process for Dashlinx"

# Drop database
echo "Removing DashLinx database"
sudo mysql -e "DROP DATABASE IF EXISTS dashlinx;" 
sudo mysql -e "DROP USER 'dashlinx'@'localhost';" 

# Stop services if they are running
if systemctl is-active --quiet nginx; then
    sudo systemctl stop nginx
fi

if systemctl is-active --quiet mysql; then
    sudo systemctl stop mysql
fi

if systemctl is-active --quiet php8.1-fpm; then
    sudo systemctl stop php8.1-fpm
fi

if systemctl is-active --quiet php-fpm; then
    sudo systemctl stop php-fpm
fi

# Remove DashLinx files
sudo rm -rf /var/www/html/*


# Remove Nginx and PHP configurations
sudo rm /etc/nginx/sites-available/default
sudo rm /etc/nginx/nginx.conf
sudo rm /etc/php/8.1/fpm/php.ini 
sudo rm /etc/php/8.1/cli/php.ini

# Uninstall packages if they are installed
if dpkg-query -W -f='${Status}' nginx 2>/dev/null | grep -q "ok installed"; then
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y nginx
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y nginx-light
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y nginx-common
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y nginx-core
fi

if dpkg-query -W -f='${Status}' mysql-server 2>/dev/null | grep -q "ok installed"; then
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y mysql-server
fi

if dpkg-query -W -f='${Status}' php8.1-fpm 2>/dev/null | grep -q "ok installed"; then
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y php8.1-fpm
fi

if dpkg-query -W -f='${Status}' php8.1-mysql 2>/dev/null | grep -q "ok installed"; then
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y php8.1-mysql
fi

if dpkg-query -W -f='${Status}' php-fpm 2>/dev/null | grep -q "ok installed"; then
    sudo DEBIAN_FRONTEND=noninteractive apt-get remove --purge -y php-fpm
fi



# Autoremove
sudo apt-get autoremove -y

# Remove logs
sudo rm -rf /var/log/dashlinx

sudo dpkg --configure -a
sudo apt --fix-broken install
sudo apt-get clean
sudo apt-get autoclean

echo "Uninstall process for Dashlinx completed"
