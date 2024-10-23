# How To Deploy

## Install Required Software
```
sudo apt update

sudo apt install git composer zip
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip
sudo apt install nginx
```

### Install Composer
```
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Clone the Project
```
cd /var/www
sudo git clone https://github.com/arismetyogi/hcis.git
```

## Configuration

```
cd /var/www/hcis
```

### Environment Variables
Open and edit `.env`
```
APP_HOST=production
APP_URL={host/domain}
APP_DEBUG=false

DB_HOST={MYSQL Host IP}
DB_DATABASE={Database Name}
DB_USERNAME={Database Username}
DB_PASSWORD={Database Password}
```

### Permision
```
sudo chown -R www-data:www-data .
sudo chown -R www-data:www-data /var/www/hcis/storage
sudo chown -R www-data:www-data /var/www/hcis/bootstrap/cache

sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chomd 755 {} \;
```
### Run the required command
```
sudo -u www-data composer install --no-dev --optimize-autoloader

sudo mkdir /var/www/.npm
sudo chown -R 33:33 "/var/www/.npm"

sudo npm install
sudo npm run build

sudo composer require livewire/livewire (yes)

sudo php artisan optimize
sudo php artisan migrate
sudo php artisan db:seed

sudo php artisan vendor:publish --force --tag=livewire:assets
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo php artisan filament:assets
sudo php artisan filament:cache-components
```

## Configure the Web Server (Nginx)
```
cd /etc/nginx
```

### Create a new Nginx server block for your application.
```
cd /etc/nginx/sites-available
sudo vi hcis.conf
or
sudo nano hcis.conf
```

#### Copy this config and save
```
server {
    listen 80;
    server_name your-domain.com; //change this with ip, subdomain, or domain
    root /var/www/hcis/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

#### Link the configuration, restart Nginx and php.
```
sudo ln -s /etc/nginx/sites-available/hcis.conf /etc/nginx/sites-enabled/
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
```
