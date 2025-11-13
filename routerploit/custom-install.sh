# Setting up the server
# called via root's crontab

INSTALL_DIR="/home/routerploit"
SERVICENAME="routerploit"

# Generating TLS certificate for Nginx
mkdir -p /etc/nginx/ssl | sed -e "s/^/[Nginx] /"
openssl ecparam -name secp384r1 -genkey -noout -out /etc/nginx/ssl/nginx.key | sed -e "s/^/[Nginx] /"
openssl req -new -key /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.csr -subj "/C=//ST=//L=//O=//OU=//CN=//emailAddress=" | sed -e "s/^/[Nginx] /"
openssl x509 -req -in /etc/nginx/ssl/nginx.csr -signkey /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt | sed -e "s/^/[Nginx] /"

# Restart nginx to reload certificate
systemctl restart nginx

# Security
sql_root_password=$(openssl rand -base64 32 | tr -d '/+=' | cut -c1-32)
echo "SQL: user=root password=$sql_root_password" >> $INSTALL_DIR/setup.info
sed -i "s/sql_root_password/$sql_root_password/g" $INSTALL_DIR/mariadb-secure-installation.sh

sql_saarsec_password=$(openssl rand -base64 32 | tr -d '/+=' | cut -c1-32)
echo "SQL: user=saarsec password=$sql_saarsec_password" >> $INSTALL_DIR/setup.info
sed -i "s/^\$db_passwd = .*/\$db_passwd = \"$sql_saarsec_password\";/" $INSTALL_DIR/www/config/database.php
sed -i "s/sql_saarsec_password/$sql_saarsec_password/g" $INSTALL_DIR/routerploit.sql

config_pepper=$(openssl rand -base64 32 | tr -d '/+=' | cut -c1-32)
echo "Config: pepper=$config_pepper" >> $INSTALL_DIR/setup.info
sed -i "s/^\$pepper = .*/\$pepper = \"$config_pepper\";/" $INSTALL_DIR/www/config/security.php

config_auth_code_seed="$(openssl rand -hex 7)"
echo "Config: auth_code_seed=$config_auth_code_seed" >> $INSTALL_DIR/setup.info
sed -i "s/^\$auth_code_seed = .*/\$auth_code_seed = 0x$config_auth_code_seed;/" $INSTALL_DIR/www/config/security.php

# Securing the MariaDB
chmod +x $INSTALL_DIR/mariadb-secure-installation.sh | sed -e "s/^/[MariaDB] /"
$INSTALL_DIR/mariadb-secure-installation.sh | sed -e "s/^/[MariaDB] /"

# Set up database
sed -i "s/utf8mb4_0900_as_cs/utf8mb4_general_ci/g" $INSTALL_DIR/routerploit.sql
sed -i "s/product_router1/$(uuidgen)/g" $INSTALL_DIR/routerploit.sql
sudo -u $SERVICENAME mariadb $SERVICENAME < $INSTALL_DIR/routerploit.sql
