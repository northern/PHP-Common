#!/bin/bash

# Add swap space.
sh /vagrant/vagrant/swap.sh

# Add PHP repos.
add-apt-repository ppa:ondrej/php

# Install prep.
apt-get update
#apt-get -y upgrade

# Install basic necessities.
apt-get -y install ntp git htop tree zip unzip python-simplejson
systemctl restart ntp.service

# Install php.
PHP_VERSION="5.6"

apt-get -y install \
  php${PHP_VERSION} \
  php${PHP_VERSION}-cli \
  php${PHP_VERSION}-curl \
  php${PHP_VERSION}-mcrypt \
  php${PHP_VERSION}-intl \
  php${PHP_VERSION}-json \
  php${PHP_VERSION}-xml \
  php${PHP_VERSION}-mbstring

sed -i "s/;date timezone =/date timezone = UTC/" /etc/php/${PHP_VERSION}/apache2/php.ini
sed -i "s/;date timezone =/date timezone = UTC/" /etc/php/${PHP_VERSION}/cli/php.ini

sed -i "s/;realpath_cache_size = 16k/realpath_cache_size = 4096k/" /etc/php/${PHP_VERSION}/apache2/php.ini
sed -i "s/;realpath_cache_size = 16k/realpath_cache_size = 4096k/" /etc/php/${PHP_VERSION}/cli/php.ini

sed -i "s/;realpath_cache_ttl = 120/realpath_cache_size = 7200/" /etc/php/${PHP_VERSION}/apache2/php.ini
sed -i "s/;realpath_cache_ttl = 120/realpath_cache_size = 7200/" /etc/php/${PHP_VERSION}/cli/php.ini

# Install Composer.
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/bin/composer

