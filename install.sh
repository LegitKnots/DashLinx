#!/bin/bash

log_file="/var/log/dashlinx/install.log"
sudo mkdir -p /var/log/dashlinx
sudo rm -f "$log_file"
sudo touch "$log_file"
sudo chmod 755 "$log_file"


echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo " ____            _     _     _ " | sudo tee -a "$log_file"
echo "|  _ \\  __ _ ___| |__ | |   (_)_ __ __  __ " | sudo tee -a "$log_file"
echo "| | | |/ _\` / __| '_ \\| |   | | '_ \\\\ \/ / " | sudo tee -a "$log_file"
echo "| |_| | (_| \\__ \\ | | | |___| | | | |>  < " | sudo tee -a "$log_file"
echo "|____/ \\__,_|___/_| |_|_____|_|_| |_/_/\\_\\" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"


# Update the server
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Updating the server...." | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get update | sudo tee -a "$log_file" >/dev/null
sudo DEBIAN_FRONTEND=noninteractive apt-get upgrade -y | sudo tee -a "$log_file" >/dev/null
sudo DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -y | sudo tee -a "$log_file" >/dev/null
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Updated!" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"



# Install LEMP stack
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing nginx" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install nginx -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing mysql-server" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install mysql-server -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing php8.1-fpm" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install php8.1-fpm -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing php8.1-mysql" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install php8.1-mysql -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing git" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install git -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Installing php-fpm" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo DEBIAN_FRONTEND=noninteractive apt-get install php-fpm -y | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"


echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "All packages installed" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"






echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Creating Configuration" | sudo tee -a "$log_file"
echo "-------- -------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"


title="DashLinx"
tab="DashLinx"
port="80"

dbpsw="/var/log/dashlinx/database_password.log"

password=$(openssl rand -base64 6 | tr -d '+/' | cut -c1-8)

if [ -f "$dbpsw" ]; then
  password=$(sudo cat "$dbpsw")
  echo "Found existing password for database"
fi



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

sudo rm -f /var/log/dashlinx/database_password.log | sudo tee -a "$log_file"
sudo touch /var/log/dashlinx/database_password.log | sudo tee -a "$log_file"
echo "$password" | sudo tee -a /var/log/dashlinx/database_password.log >/dev/null

echo "Title: $title" | sudo tee -a "$log_file"
echo "Browser Tab Title: $tab" | sudo tee -a "$log_file"
echo "Port: $port" | sudo tee -a "$log_file"
echo "Database Username: dashlinx" | sudo tee -a "$log_file"
echo "Database Password: $password" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"


echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Cleaning Nginx" | sudo tee -a "$log_file"

if [ -d "/var/www/html/src/images/uploaded" ]; then
  echo "Found old instalation" | sudo tee -a "$log_file"
  echo "Saving Files" | sudo tee -a "$log_file"
  sudo rm -f -R "/tmp/uploaded"
  sudo rm -f "/tmp/background.png"
  sudo cp -R "/var/www/html/src/images/uploaded" "/tmp/uploaded"
  sudo cp "/var/www/html/src/images/background.png" "/tmp/background.png"
  restore=true
else
  restore=false
  echo "No images to restore" | sudo tee -a "$log_file"
fi

sudo rm -R /var/www/html/* | sudo tee -a "$log_file" >/dev/null
echo "Done" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"

echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Downloading DashLinx" | sudo tee -a "$log_file"
git clone https://github.com/AJPNetworks/DashLinx.git | sudo tee -a "$log_file" >/dev/null
echo "Downloaded" | sudo tee -a "$log_file"
echo "Installing" | sudo tee -a "$log_file"
sudo cp -R DashLinx/app/* /var/www/html
sudo rm -f -R DashLinx
echo "Installed" | sudo tee -a "$log_file"
echo "Configuring DashLinx" | sudo tee -a "$log_file"

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

if [ "$restore" = true ]; then 
  sudo cp -R "/tmp/uploaded" "/var/www/html/src/images"
  sudo cp  "/tmp/background.png" "/var/www/html/src/images/background.png"
  sudo rm -f -R "/tmp/uploaded"
  sudo rm -f -R "/var/www/html/src/images/uploaded"
  sudo rm -f "/tmp/background.png"
  echo "Images Restored" | sudo tee -a "$log_file"
fi

echo "Configured" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"



echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Configuring Nginx" | sudo tee -a "$log_file"

# Define configurations
nginx_conf="/etc/nginx/nginx.conf"
nginx_directive="client_max_body_size"
max_upload_size="20M"



# Function to update Nginx configuration
update_nginx_configuration() {
  # Update Nginx Configuration
  sudo sed -i "/^http {/a \\\t$nginx_directive $max_upload_size;" "$nginx_conf"

  # Update Nginx default site
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

  echo "Configuration file updated" | sudo tee -a "$log_file"
}

# Update Nginx configuration
if [ -f "$nginx_conf" ]; then
  sudo sed -i "s/^listen .*;$/listen $port;/" "$nginx_conf" | sudo tee -a "$log_file" >/dev/null
  echo "Nginx port changed to $port."
else
  echo "Nginx configuration file not found. Port not changed."
fi

# Call the update_nginx_configuration function
update_nginx_configuration
echo "Done!" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"


echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Configuring PHP-FPM" | sudo tee -a "$log_file"

# Get PHP version
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')

# Define PHP configuration file paths
php_ini_file_1="/etc/php/8.1/fpm/php.ini"
php_ini_file_2="/etc/php/8.1/cli/php.ini"

# Function to update PHP configuration
update_php_configuration() {
  # Update PHP Configuration
  sudo sed -i "s/^post_max_size.*/post_max_size = $max_upload_size/" "$php_ini_file_1"
  sudo sed -i "s/^upload_max_filesize.*/upload_max_filesize = $max_upload_size/" "$php_ini_file_1"

  sudo sed -i "s/^post_max_size.*/post_max_size = $max_upload_size/" "$php_ini_file_2"
  sudo sed -i "s/^upload_max_filesize.*/upload_max_filesize = $max_upload_size/" "$php_ini_file_2"

  # Configure PHP-FPM
  sudo sed -i "s|listen = /run/php/php.*-fpm.sock|listen = /run/php/php${PHP_VERSION}-fpm.sock|" /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
  sudo tee -a "$log_file" >/dev/null <<EOF
PHP-FPM configuration updated for version $PHP_VERSION
EOF
}

# Call the update_php_configuration function
update_php_configuration

echo "Done!" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"





echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Creating Database" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
sudo mysql -e "CREATE DATABASE IF NOT EXISTS dashlinx;" 2>/dev/null | sudo tee -a "$log_file" >/dev/null
sudo mysql -e "CREATE USER 'dashlinx'@'localhost' IDENTIFIED BY '$password';" 2>/dev/null | sudo tee -a "$log_file" >/dev/null
sudo mysql -e "GRANT ALL PRIVILEGES ON dashlinx.* TO 'dashlinx'@'localhost';" 2>/dev/null | sudo tee -a "$log_file" >/dev/null
echo "" | sudo tee -a "$log_file"
echo "Created" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"


# Check if ufw is installed
if command -v ufw >/dev/null 2>&1; then
    echo "ufw is installed"
    echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
    echo "Opening firewall port $port" | sudo tee -a "$log_file"
    sudo ufw allow $port | sudo tee -a "$log_file"
    echo "Reloading Firewall" | sudo tee -a "$log_file"
    sudo ufw reload | sudo tee -a "$log_file"
    echo "Reloaded" | sudo tee -a "$log_file"
    echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
    echo "" | sudo tee -a "$log_file"
else
    echo "ufw is not installed. Skipping firewall configuration." | sudo tee -a "$log_file"
fi


# Start and enable Nginx
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Starting Nginx" | sudo tee -a "$log_file"
sudo systemctl start nginx | sudo tee -a "$log_file"
sudo systemctl enable nginx | sudo tee -a "$log_file"
echo "Started" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"

# Start and enable MySQL
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Starting MySQL" | sudo tee -a "$log_file"
sudo systemctl start mysql | sudo tee -a "$log_file"
sudo systemctl enable mysql | sudo tee -a "$log_file"
echo "Started" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"

# Start and enable PHP-FPM
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Starting PHP" | sudo tee -a "$log_file"
sudo systemctl start php8.1-fpm | sudo tee -a "$log_file"
sudo systemctl enable php8.1-fpm | sudo tee -a "$log_file"
echo "Started" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Setting permissions" | sudo tee -a "$log_file"
sudo chown -R www-data:www-data /var/www/html | sudo tee -a "$log_file"
echo "Done" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Restarting Services" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
sudo service php8.1-fpm restart | sudo tee -a "$log_file"
sudo service nginx restart | sudo tee -a "$log_file"
echo "" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"
echo "Nginx is listening on port $port" | sudo tee -a "$log_file"
echo "INSTALLED" | sudo tee -a "$log_file"
ip_address=$(ip -4 route get 1.1.1.1 | awk '/src/ {print $7}' | sudo tee -a "$log_file")
echo "You can now go to http://$ip_address:$port and configure DashLinx!" | sudo tee -a "$log_file"
echo "-------------------------------------------------------------" | sudo tee -a "$log_file"