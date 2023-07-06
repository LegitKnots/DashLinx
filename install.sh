#!/bin/bash
sudo mkdir /var/log/dashlinx
sudo touch /var/log/dashlinx/install.log

log_file="/var/log/dashlinx/install.log"


# Update the server
echo "Updating the server..."
sudo apt update
sudo apt upgrade -y
sudo apt dist-upgrade -y
echo "Server Updated"

# Install LEMP stack
echo "Installing necessary packages"
echo "Installing nginx"
sudo apt install nginx -y
echo "Installing mysql-server"
sudo apt install mysql-server -y
echo "Installing php8.1-fpm"
sudo apt install php8.1-fpm -y
echo "Installing php8.1-mysql"
sudo apt install php8.1-mysql -y
echo "Installing Git"
sudo apt install git -y
echo "Installing php-fpm"
sudo apt install php-fpm -y
echo "All packages installed"




title="DashLinx"
tab="DashLinx"
password=$(openssl rand -base64 6 | tr -d '+/' | cut -c1-8)
port="80"

# Parse command-line options and arguments
while getopts "t:b:P:p:" opt; do
  case $opt in
    t)
      title=$OPTARG
      ;;
    b)
      tab=$OPTARG
      ;;
    P)
      password=$OPTARG
      ;;
    p)
      port=$OPTARG
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
  esac
done

echo "Cleaning Nginx"
sudo rm -R /var/www/html/*
echo "Done"

echo "Downloading DashLinx.."
git clone https://github.com/AJPNetworks/DashLinx.git
sudo cp -R DashLinx/app/* /var/www/html
sudo rm -R DashLinx
echo "Downloaded"
echo "Configuring DashLinx"

sudo rm /var/www/html/setup.php
echo "<?php
\$page_title = '$title';
\$tab_title = '$tab';
\$db_host = 'localhost';
\$db_user = 'dashlinx';
\$db_pass = '$password';
\$db_name = 'dashlinx';
\$root_dir = '/var/www/html';
?>" | sudo tee /var/www/html/setup.php >/dev/null
echo "Configured"





echo "Configuring Nginx"
# Function to check and return the valid Nginx configuration file path
find_nginx_conf() {
  local nginx_conf_path="/etc/nginx/nginx.conf"
  local alt_nginx_conf_path="/etc/nginx/conf/nginx.conf"

  if [ -f "$nginx_conf_path" ]; then
    echo "$nginx_conf_path"
  elif [ -f "$alt_nginx_conf_path" ]; then
    echo "$alt_nginx_conf_path"
  else
    echo "Nginx configuration file not found."
  fi
}

# Get the valid Nginx configuration file path
nginx_conf_file=$(find_nginx_conf)

# Check if the file was found
if [ "$nginx_conf_file" != "Nginx configuration file not found." ]; then
  # Set the desired port number

  # Use sed to change the port in the configuration file
  sudo sed -i "s/^listen .*;$/listen $port;/" "$nginx_conf_file"

  echo "Nginx port changed to $port."
else
  echo "Nginx configuration file not found. Port not changed."
fi


# Get PHP version
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')

# Configure PHP-FPM
sudo sed -i "s|listen = /run/php/php.*-fpm.sock|listen = /run/php/php${PHP_VERSION}-fpm.sock|" /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf

# Configure Nginx
sudo tee /etc/nginx/sites-available/default >/dev/null <<EOT
server {
    listen $port default_server;
    listen [::]:$port default_server;

    root /var/www/html;
    index index.php index.html index.htm;

    server_name _;

    location / {
        try_files \$uri \$uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
    }
}
EOT

# Restart services
sudo service php${PHP_VERSION}-fpm restart
sudo service nginx restart
echo "Configured"

echo "Creating Database"
sudo mysql -e "CREATE DATABASE dashlinx;"
sudo mysql -e "CREATE USER 'dashlinx'@'localhost' IDENTIFIED BY '$password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON dashlinx.* TO 'dashlinx'@'localhost';"
echo "Created"


#sudo ufw allow 80
echo "Opening firewall port $port"
sudo ufw allow $port
echo "Reloading Firewall"
sudo ufw reload
echo "Reloaded"

# Start and enable Nginx
echo "Starting Nginx"
sudo systemctl start nginx
sudo systemctl enable nginx
echo "Started"

# Start and enable MySQL
echo "Starting MySQL"
sudo systemctl start mysql
sudo systemctl enable mysql
echo "Started"

# Start and enable PHP-FPM
echo "Starting PHP"
sudo systemctl start php8.1-fpm
sudo systemctl enable php8.1-fpm
echo "Started"

echo "Setting permissions"
sudo chown -R www-data:www-data /var/www/html
echo "Done"

sudo service php${PHP_VERSION}-fpm restart
sudo service nginx restart

echo "Nginx is listening on port $port"
echo "INSTALLED"
echo "You can now go to http://$ip_address:$port and configure DashLinx!"