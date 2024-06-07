# Etsy Merchant Feeds

This is a very simple Laravel app to generate Google Merchant Data Feeds from an Etsy Shop.

## Features

 - Build simple XML feeds from your Etsy store listings in a format that's compatible with Google Merchant Feeds, for use in Google Shopping Ads, Facebook, Instagram product tagging, etc.
 - The product URLs will use your own domain and redirect from there to Etsy. This will allow you to verify your own domain within Instagram, rather than using Etsy's, which  is required in order to be able to use product tagging withing Instagram.
 - Optionally, supply custom UTM tags for your product URLs, in order to enable tracking in Google Analytics.
 - Optionally, supply a custom Google Product Category for your feed.
 - You may connect multiple Etsy stores, or the same store multiple times, with different UTM tags.  
 - You can choose to update feeds on various configurable schedules, or manually.
 - You may redirect the homepage to your Etsy store, if desired.

### Limitations

 - You can only supply one Google Product Category for the entirety of your feed. You cannot (yet) select categories per product.
 - Multi-store support is experimental.
 - You need to be comfortable hosting a simple PHP app.
 - The name is boring, and the logo is ugly 😬

## Pre-requisites

The following is needed in order to install Etsy Merchant Feeds:

- You need a server running PHP 8.3 with the following extensions installed:
  - `php8.3-cli`
  - `php8.3-common`
  - `php8.3-fpm`
  - `php8.3-opcache`
  - `php8.3-readline`
  - `php8.3-sqlite3`
  - `php8.3-xml`
- You also need a webserver (e.g. nginx), and a domain or subdomain pointing to your server.
- Recommended: Set up HTTPS usign Let's Encrypt or similar.
- Finally, you need some willingness to dive into code.

## Get your Etsy API key

In order to use this tool, you need an Etsy API key. Etsy can take some time (several days) to approve applications for such a key, so it's recommended to start this right away:

1. Go to [etsy.com/developers/register](https://www.etsy.com/developers/register) 
2. Fill in the required details. As part of this, you'll need to provide a callback URL. This is `https://example.com/etsy/callback` (Replace `example.com` with your own domain).
3. Take a note of your **KEYSTRING** (the **SHARED SECRET** is not required for this tool) 

## Install Etsy Merchant Feeds.

1. Clone this repo. The rest of this guide will assume you have cloned this into `/var/www/example.com`.
2. Change into your directory and copy the `.env.example` file:
```bash
cd /var/www/example.com
cp .env.example .env
```
3. Open the `.env` file and adjust these values for your environment. You will need to provide at least the following configuration options (replace `https://example.com` with your own URL and `{YOUR_KEYSTRING}` with the KEYSTRING you received from Etsy) 
   - `APP_URL=https://example.com`
   - `ETSY_KEYSTRING={YOUR_KEYSTRING}`
4. Run the following commands to install all requirements:
```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 
php artisan key:generate --force
php artisan migrate --force
php artisan optimize 
npm install
npm run build 
```
5. Run `php artisan user:register` to register a user.
6. Open your application in the browser at `https://example.com/dashboard`. Log in and follow the prompts to connect to your Etsy store.
7. Set up your cronjobs by runing `crontab -e` and adding the following line:
```
* * * * * cd /var/www/example.com && php artisan schedule:run >> /dev/null 2>&1
```

### Nginx sample configuration

The following sample configuration is taken from the official [Laravel docs](https://laravel.com/docs/11.x/deployment#server-configuration).

Please ensure, like the configuration below, your web server directs all requests to your application's `public/index.php` file. You should never attempt to move the `index.php` file to your project's root, as serving the application from the project root will expose many sensitive configuration files to the public Internet:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /var/www/example.com/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Update Etsy Merchant Feeds

Any time you wish to update Etsy Merchant Feeds follow these steps:

1. `git pull` your clone of the repo.
2. Run the following commands to update everything:
```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan optimize
npm install
npm run build
```
