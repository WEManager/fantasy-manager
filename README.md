#WEM - Fantasy Manager

## Setup

### Install PHP8
```bash
sudo add-apt-repository ppa:ondrej/php

curl -sL https://deb.nodesource.com/setup_18.x | sudo bash -

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -

echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list

sudo apt update && sudo apt upgrade -y

## Install PHP/webserver/database
sudo apt install -y php8.2-fpm php8.2-mbstring php8.2-curl php8.2-bz2 php8.2-zip php8.2-xml php8.2-gd php8.2-mysql php8.2-intl php8.2-sqlite3 php8.2-soap php8.2-bcmath php8.2-memcached php8.2-redis php8.2-xmlrpc apt-transport-https nginx mysql-client mysql-server

## If will you use supabase (postgresql)
sudo apt install php-pgsql

## Finaly restart apache/php
sudo service apache2 reload
```

You'll nedd install [Composer]([https](https://getcomposer.org/doc/00-intro.md)) and [NodeJS](https://nodejs.org/en/) to.

#### Database
I recomend use [Supabase](https://supabase.com/)


## Start
Folow normal flow of [Laravel 9.x](https://laravel.com/docs/9.x)